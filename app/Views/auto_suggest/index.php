<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Suggest</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/jquery/jquery.dataTables.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <style>
        .suggestion-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .suggestion-item {
            padding: 8px;
            border: 1px solid #ddd;
            margin-top: -1px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Auto Suggest Search</h2>
        <input type="text" id="searchInput" class="form-control" placeholder="Search..." />
        <table class="table mt-2" id="suggestionsTable">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Full Name</th>
                    <th>Job Title</th>
                    <th>Department</th>
                    <th>Line</th>
                    <th>Competency</th>
                    <th>Indicator</th>
                    <th>Achieved</th>
                </tr>
            </thead>
            <tbody id="suggestionsList"></tbody>
        </table>
    </div>

    <!-- Modal for Indicators -->
    <div class="modal fade" id="indicatorsModal" tabindex="-1" aria-labelledby="indicatorsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="indicatorsModalLabel">Indicators</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Indicator</th>
                                <th>Achieved</th>
                            </tr>
                        </thead>
                        <tbody id="indicatorsList"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/js/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery/jquery.dataTables.min.js'); ?>"></script>
    <script>
        $(document).ready(function() {
            var table = $('#suggestionsTable').DataTable();

            $('#searchInput').on('input', function() {
                var searchTerm = $(this).val();
                table.clear(); // Clear previous data

                if (searchTerm.length > 2) {
                    $.ajax({
                        url: 'auto_suggest/search',
                        type: 'POST',
                        data: {
                            searchTerm: searchTerm
                        },
                        success: function(response) {
                            console.log(response);
                            try {
                                var suggestions = response;
                                var groupedData = {};

                                // Grouping data
                                suggestions.forEach(function(item) {
                                    var key = item.txtFullName + '|' + item.txtJobTitle + '|' + item.txtDepartmentName + '|' + item.txtLine + '|' + item.txtCompetency + '|' + item.txtIndicator;

                                    if (!groupedData[key]) {
                                        groupedData[key] = {
                                            photo: item.txtPhoto,
                                            fullName: item.txtFullName,
                                            jobTitle: item.txtJobTitle,
                                            department: item.txtDepartmentName,
                                            line: item.txtLine,
                                            competencies: [],
                                        };
                                    }

                                    groupedData[key].competencies.push({
                                        competency: item.txtCompetency,
                                        indicator: item.txtIndicator,
                                        achieved: item.bitAchieved === "1" ? "Yes" : "No"
                                    });
                                });

                                // Add grouped data to table
                                for (var key in groupedData) {
                                    var group = groupedData[key];
                                    var achievedCount = 0; // Counter for achieved indicators
                                    var totalCount = group.competencies.length; // Total indicators

                                    group.competencies.forEach(function(comp) {
                                        if (comp.achieved === "Yes") {
                                            achievedCount++;
                                        }
                                    });

                                    var achievementPercentage = totalCount > 0 ? ((achievedCount / totalCount) * 100).toFixed(2) + '%' : '0%';

                                    var firstRow = [
                                        group.photo ? '<img src="<?php echo base_url("' + group.photo + '"); ?>" alt="Photo" style="width: 50px; height: 50px; object-fit: cover;">' : 'No photo',
                                        group.fullName,
                                        group.jobTitle,
                                        group.department,
                                        group.line,
                                        group.competencies.length > 0 ? group.competencies[0].competency : '',
                                        '<button class="btn btn-link show-indicators" data-indicators=\'' + JSON.stringify(group.competencies) + '\'>Show Indicators</button>',
                                        achievementPercentage // Tampilkan persentase pencapaian di kolom Achieved
                                    ];

                                    // Add main row
                                    table.row.add(firstRow).draw();
                                }

                                // Modal show indicators click event
                                $('.show-indicators').on('click', function() {
                                    var indicators = $(this).data('indicators');
                                    var indicatorsList = $('#indicatorsList');
                                    indicatorsList.empty(); // Clear previous indicators

                                    // Loop through each competency to add all indicators
                                    indicators.forEach(function(comp) {
                                        var achievedStatus = comp.achieved === "Yes" ? "Yes" : "No";

                                        // Tentukan kelas CSS untuk indikator yang belum dicapai
                                        var rowClass = achievedStatus === "No" ? 'table-warning' : ''; // Beri warna jika tidak ada di tabel

                                        // Tambahkan row dengan kelas berbeda jika indikator tidak tercapai
                                        indicatorsList.append(
                                            '<tr class="' + rowClass + '">' +
                                            '<td>' + comp.indicator + '</td>' +
                                            '<td>' + achievedStatus + '</td>' +
                                            '</tr>'
                                        );
                                    });

                                    $('#indicatorsModal').modal('show'); // Show modal
                                });

                            } catch (error) {
                                console.error("Error parsing JSON:", error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                        }
                    });
                } else {
                    table.clear(); // Clear the table if the search term is less than 3 characters
                }
            });

        });
    </script>
</body>

</html>
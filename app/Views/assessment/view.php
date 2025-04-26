<div class="container">
    <h1>View Assessment</h1>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>Assessment ID</th>
                <td><?= $assessment['intAssessmentID'] ?? '-' ?></td>
            </tr>
            <tr>
                <th>User ID</th>
                <td><?= $assessment['intUserID'] ?? '-' ?></td>
            </tr>
            <tr>
                <th>Line</th>
                <td><?= $assessment['intLineID'] ?? '-' ?></td>
            </tr>
            <tr>
                <th>Job Title</th>
                <td><?= $assessment['intJobTitleID'] ?? '-' ?></td>
            </tr>
            <tr>
                <th>Competency</th>
                <td><?= $assessment['intCompetencyID'] ?? '-' ?></td>
            </tr>
            <tr>
                <th>Indicator</th>
                <td><?= $assessment['intIndicatorID'] ?? '-' ?></td>
            </tr>
            <tr>
                <th>Result</th>
                <td><?= isset($assessment['bitResult']) && $assessment['bitResult'] == 1 ? 'Pass' : 'Fail' ?></td>
            </tr>
            <tr>
                <th>Assessed Date</th>
                <td><?= $assessment['dtmAssessedDate'] ?? '-' ?></td>
            </tr>
            <tr>
                <th>Assessed By</th>
                <td><?= $assessment['txtAssessedBy'] ?? '-' ?></td>
            </tr>
        </tbody>
    </table>
    <a href="/assessment" class="btn btn-secondary">Back to List</a>
</div>
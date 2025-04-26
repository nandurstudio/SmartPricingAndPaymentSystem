<h1>Detail Tool</h1>

<p><strong>Nama Tool:</strong> <?= esc($tool['txtToolName']) ?></p>
<p><strong>Deskripsi:</strong> <?= esc($tool['txtToolDesc']) ?></p>
<p><strong>Status:</strong> <?= $tool['bitActive'] ? 'Aktif' : 'Tidak Aktif' ?></p>
<p><strong>Ditambahkan oleh:</strong> <?= esc($tool['txtInsertedBy']) ?></p>
<p><strong>Tanggal Ditambahkan:</strong> <?= esc($tool['dtmInsertedDate']) ?></p>
<p><strong>GUID:</strong> <?= esc($tool['txtGUID']) ?></p>

<a href="/tool">Kembali</a>
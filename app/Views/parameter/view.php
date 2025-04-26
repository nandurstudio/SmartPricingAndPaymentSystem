<h1>Detail Parameter</h1>

<p><strong>Nama Parameter:</strong> <?= esc($parameter['txtParameterName']) ?></p>
<p><strong>Deskripsi:</strong> <?= esc($parameter['txtParameterDesc']) ?></p>
<p><strong>Status:</strong> <?= $parameter['bitActive'] ? 'Aktif' : 'Tidak Aktif' ?></p>
<p><strong>Ditambahkan oleh:</strong> <?= esc($parameter['txtInsertedBy']) ?></p>
<p><strong>Tanggal Ditambahkan:</strong> <?= esc($parameter['dtmInsertedDate']) ?></p>
<p><strong>GUID:</strong> <?= esc($parameter['txtGUID']) ?></p>

<a href="/parameter">Kembali</a>
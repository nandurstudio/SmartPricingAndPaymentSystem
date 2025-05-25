REM clean_writable.bat
REM Hapus isi folder writable/logs, writable/cache, writable/session (kecuali index.html)
REM HANYA UNTUK DEVELOPMENT/LOCAL! JANGAN JALANKAN DI PRODUCTION!
REM Pastikan backup data penting sebelum menjalankan script ini.

@echo off
echo Bersihin writable/logs kecuali index.html...
for %%F in (writable\logs\*) do (
    if /I not "%%~nxF"=="index.html" del "%%F"
)

echo Bersihin writable/cache kecuali index.html...
for %%F in (writable\cache\*) do (
    if /I not "%%~nxF"=="index.html" del "%%F"
)

echo Bersihin writable/session kecuali index.html...
for %%F in (writable\session\*) do (
    if /I not "%%~nxF"=="index.html" del "%%F"
)

echo Bersihin writable/uploads (optional, pastikan aman)...
REM for %%F in (writable\uploads\*) do (
REM     if /I not "%%~nxF"=="index.html" del "%%F"
REM )

echo Bersih-bersih selesai!
pause

public function checkLogWritable()
{
    $isWritable = is_writable(WRITEPATH . 'logs');
    echo $isWritable ? 'Writable' : 'Not Writable';
}

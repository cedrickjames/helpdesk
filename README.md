When the user cant upload, please check the folder properties. Maybe you should change the owner of the folder via terminal ( sudo chown apache:apache upload_files
sudo chcon -t httpd_sys_rw_content_t upload_files)

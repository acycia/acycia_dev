echo on
Q:
cd \bbdd/
mysqldump  -hlocalhost --password=ac2006 --user=acycia_root acycia_intranet > acycia_intranet_%time:~0,2%.sql
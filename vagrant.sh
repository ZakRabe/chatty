#/bin/bash
echo "********************************************************************************"
echo "*                                                                              *"
echo "*            This application will now be installed and configured             *"
echo "*            =====================================================             *"
echo "*                                                                              *"
echo "*           This may take a few minutes. Go get a coffee and I'll let          *"
echo "*           you know when I'm done.                                            *"
echo "*                                                                              *"
echo "********************************************************************************"


VAGRANT_WWW_PATH="/var/www/application"
VAGRANT_APP_URL="application.local"
VAGRANT_CONF_FILES_PATH="$VAGRANT_WWW_PATH/.vagrant/conf"

VAGRANT_EXTRA_APT_PACKAGES="htop nano"

VAGRANT_INSTALL_PHP=1
VAGRANT_INSTALL_APACHE=1
VAGRANT_INSTALL_POSTFIX=0

VAGRANT_APACHE_MODULES="rewrite"

VAGRANT_INSTALL_MYSQL=1
VAGRANT_MYSQL_SUPER_PASS="r3act0r"
VAGRANT_MYSQL_APP_DB="application"
VAGRANT_MYSQL_APP_USER="application"
VAGRANT_MYSQL_APP_PASS="password"
VAGRANT_MYSQL_SQL_IMPORT=1
VAGRANT_MYSQL_SQL_PATH="$VAGRANT_WWW_PATH/protected/data/schema.sql"

VAGRANT_INSTALL_PGSQL=0
VAGRANT_PGSQL_APP_DB="application"
VAGRANT_PGSQL_APP_USER="application"
VAGRANT_PGSQL_APP_PASS="password"
VAGRANT_PGSQL_CONF_PATH="/etc/postgresql/9.3/main/postgresql.conf"
VAGRANT_PGSQL_HBA_PATH="/etc/postgresql/9.3/main/pg_hba.conf"

VAGRANT_INSTALL_YII=1
VAGRANT_YII_BASE_PATH="/var/www"
VAGRANT_YII_URL="https://github.com/yiisoft/yii/releases/download/1.1.14/yii-1.1.14.f0fee9.tar.gz"
VAGRANT_YII_FILE="$VAGRANT_YII_BASE_PATH/yii-1.1.14.f0fee9.tar.gz"
VAGRANT_YII_FOLDER="$VAGRANT_YII_BASE_PATH/yii-1.1.14.f0fee9"

VAGRANT_INSTALL_NODEJS=0
VAGRANT_NPM_PACKAGES="forever"

VAGRANT_INSTALL_PYTHON=0
VAGRANT_PIP_PACKAGES="uwsgi"

#
# end config
#

DEBIAN_FRONTEND=noninteractive
echo "Installing essentials ..."
apt-get install -y -qq --force-yes software-properties-common python-software-properties git > /dev/null

if [[ $VAGRANT_INSTALL_NODEJS == 1 ]]; then
  yes w | add-apt-repository ppa:richarvey/nodejs;
fi

if [[ $VAGRANT_INSTALL_PGSQL == 1 ]]; then
  apt-key adv --keyserver keyserver.ubuntu.com --recv-keys B97B0AFCAA1A47F044F244A07FCC7D46ACCC4CF8
  echo "deb http://apt.postgresql.org/pub/repos/apt/ precise-pgdg main" > /etc/apt/sources.list.d/pgdg.list
fi

echo "Updating apt ..."
apt-get update -qq

if [[ $VAGRANT_INSTALL_PHP == 1 ]]; then
  echo "Installing PHP ..."
  apt-get install -y -qq --force-yes php5 php5-gd php5-curl php5-cli php5-ffmpeg > /dev/null
  echo "Still working on it ..."
  apt-get install -y -qq --force-yes php5-mysql php-apc php-mail php-crypt-blowfish php5-uuid > /dev/null
fi

if [[ $VAGRANT_INSTALL_PYTHON == 1 ]]; then
  echo "Installing Python ..."
  apt-get install -y -qq --force-yes python2.7-dev python-pip uwsgi > /dev/null
  yes w | pip install $VAGRANT_PIP_PACKAGES;
fi

if [[ $VAGRANT_INSTALL_NODEJS == 1 ]]; then
  echo "Installing NodeJS ..."
  apt-get install -y --force-yes nodejs npm;
  npm config set registry http://registry.npmjs.org/
  yes w | npm install -g $VAGRANT_NPM_PACKAGES;
fi

if [[ $VAGRANT_INSTALL_POSTFIX == 1 ]]; then
  echo "Installing Postfix ..."
  # prepare Postfix.
  echo "postfix postfix/main_mailer_type string Internet site" > preseed.txt
  echo "postfix postfix/mailname string mail.example.com" >> preseed.txt

  # Use Mailbox format.
  debconf-set-selections preseed.txt
  apt-get install -y -qq --force-yes postfix > /dev/null

  echo "Configuring postfix ..."
  postconf -e myhostname=mail.example.com
  postconf -e mydestination="mail.example.com, example.com, localhost.localdomain, localhost"
  postconf -e mail_spool_directory="/var/spool/mail/"
  postconf -e mailbox_command=""
fi

if [[ $VAGRANT_INSTALL_APACHE == 1 ]]; then
  echo "Installing Apache ..."
  apt-get install -y -qq --force-yes apache2 libapache2-mod-php5 > /dev/null
  echo "Configuring apache ..."
  service apache2 stop
  sudo rm -rf /var/lock/apache2
  cp $VAGRANT_CONF_FILES_PATH/apache2/ports.conf /etc/apache2/ports.conf
  cp $VAGRANT_CONF_FILES_PATH/apache2/envvars /etc/apache2/envvars
  cat $VAGRANT_CONF_FILES_PATH/apache2/sites-enabled/application | \
    sed "s,application.local,$VAGRANT_APP_URL," | \
    sed "s,/var/www/application,$VAGRANT_WWW_PATH," \
    > /etc/apache2/sites-enabled/application.conf
  a2enmod $VAGRANT_APACHE_MODULES
  service apache2 start
fi

if [[ $VAGRANT_INSTALL_YII == 1 ]]; then
  echo "Installing Yii ..."
  wget -P $VAGRANT_YII_BASE_PATH $VAGRANT_YII_URL 2&>1 > /dev/null
  tar zxvf $VAGRANT_YII_FILE -C /var/www > /dev/null
  chown -R www-data:www-data $VAGRANT_YII_FOLDER
  chmod +r -R $VAGRANT_YII_FOLDER
fi

if [[ $VAGRANT_INSTALL_PGSQL == 1 ]]; then
  echo "Installing Postgres ..."
  apt-get install -y -qq --force-yes postgresql-9.3 postgresql-client-9.3 postgresql-contrib-9.3  > /dev/null

  echo "Configuring Postgres ..."
  echo "listen_addresses='*'" >> $VAGRANT_PGSQL_CONF_PATH
  echo "host all  all    0.0.0.0/0  md5" >> $VAGRANT_PGSQL_HBA_PATH

  sudo -u postgres -H psql --command "CREATE USER \"$VAGRANT_PGSQL_APP_USER\" WITH SUPERUSER PASSWORD '$VAGRANT_PGSQL_APP_PASS';"
  sudo -u postgres -H createdb -O $VAGRANT_PGSQL_APP_USER $VAGRANT_PGSQL_APP_DB
fi

if [[ $VAGRANT_INSTALL_MYSQL == 1 ]]; then
  debconf-set-selections <<< "mysql-server-5.5 mysql-server/root_password password $VAGRANT_MYSQL_SUPER_PASS"
  debconf-set-selections <<< "mysql-server-5.5 mysql-server/root_password_again password $VAGRANT_MYSQL_SUPER_PASS"
  echo "Installing MySQL ..."
  apt-get install -y -qq --force-yes mysql-server-5.5 > /dev/null
  echo "Setting up DBs ..."
  mysql --user=root --password=$VAGRANT_MYSQL_SUPER_PASS -e "CREATE DATABASE \`$VAGRANT_MYSQL_APP_DB\`;"
  mysql --user=root --password=$VAGRANT_MYSQL_SUPER_PASS -e "CREATE USER \`$VAGRANT_MYSQL_APP_USER\`@'localhost' IDENTIFIED BY '$VAGRANT_MYSQL_APP_PASS';"
  mysql --user=root --password=$VAGRANT_MYSQL_SUPER_PASS -e "GRANT ALL PRIVILEGES ON \`$VAGRANT_MYSQL_APP_DB\`.* TO \`$VAGRANT_MYSQL_APP_USER\`;"

  if [[ $VAGRANT_MYSQL_SQL_IMPORT == 1 ]]; then
    # make sure that your SQL file starts with USE `db`;
    mysql --user=root --password=$VAGRANT_MYSQL_SUPER_PASS < $VAGRANT_MYSQL_SQL_PATH
  fi

fi

if [[ $VAGRANT_EXTRA_APT_PACKAGES != "" ]]; then
  echo "Installing extra packages ..."
  apt-get install -y -qq --force-yes $VAGRANT_EXTRA_APT_PACKAGES
fi

echo "Running application setup jobs ..."
cd $VAGRANT_WWW_PATH
#/var/www/application/yiic migrate --interactive=0

#
# Any further configuration should be done here
#


echo "*********Complete!**************************************************************"
exit 0
#!/bin/bash
sudo apt-get update
sudo apt-get dist-upgrade -y
sudo apt-get install -y festival festlex-cmu festlex-poslex festlex-oald libestools1.2 unzip mp4v2-utils vorbis-tools eyed3 git sox libsox-fmt-all libav-tools php5-cli php5-curl sendemail libio-socket-ssl-perl libnet-ssleay-perl sshpass
if [ ! -d /usr/share/festival/voices/english/cmu_us_clb_arctic_clunits ] ; then
  rm -Rf /var/cmu_voices
  mkdir /var/cmu_voices
  cd /var/cmu_voices
  wget -q -t0 http://www.speech.cs.cmu.edu/cmu_arctic/packed/cmu_us_clb_arctic-0.95-release.tar.bz2
  tar xf cmu_us_clb_arctic-0.95-release.tar.bz2
  rm *.bz2
  sudo mkdir -p /usr/share/festival/voices/english
  sudo mv * /usr/share/festival/voices/english/
  sudo mv "/usr/share/festival/voices/english/cmu_us_clb_arctic" "/usr/share/festival/voices/english/cmu_us_clb_arctic_clunits"
  cd /var
  rm -Rf /var/cmu_voices
fi
if [ ! -d /var/Website ] ; then
  cd /var
  git clone https://github.com/CCHits/Website.git
  cd Website/CLI
  cp /vagrant/config_local.php .
fi

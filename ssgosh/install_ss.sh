#!/bin/bash
mkdir /home/ssgo
yum install wget -y
if [ $? -ne 0 ]; then
    echo "install wget fail"
    exit 1
fi
read -p "don't download ssgoexe?(path:/home/ssgo);input Y or N " opflag
if [[ "$opflag" -ne "Y" ]] && [[  "$opflag" -ne "y" ]];then
    wget -p /home/ssgo https://github.com/shadowsocks/shadowsocks-go/releases/download/1.2.1/shadowsocks-server.tar.gz
    if [ $? -ne 0 ]; then
        echo "wget ssgo fail"
        exit 1
    fi
    tar -xzvf /home/ssgo/shadowsocks-server.tar.gz
fi
filePath="/home/ssgo/shadowsocks-server"
if [ ! -f "$filePath" ];then
    echo "/home/ssgo/shadowsocks-server not exist"
    exit 1
fi
filePath="/home/ssgo/config.json"
if [ ! -f "$filePath" ];then
    touch $filePath
    echo ""{\"server\":\"0.0.0.0\",\"server_port\":18188,\"local_port\":1080,\"password\":\"cptbtptp\",\"method\": \"aes-256-cfb\",\"timeout\":600}"" > $filePath
    echo "config.json is created!"
else
    echo "skip create config.json"
fi
sysfcontent=""
sysfcontent="$sysfcontent""[Unit]"
sysfcontent="$sysfcontent""\nDescription=ss server"
sysfcontent="$sysfcontent""\nAfter= network.target"
sysfcontent="$sysfcontent""\n[Service]"
sysfcontent="$sysfcontent""\nType=simple"
sysfcontent="$sysfcontent""\nExecStart=/home/ssgo/shadowsocks-server -c  /home/ssgo/config.json"
sysfcontent="$sysfcontent""\nExecStop= /bin/kill -s QUIT \$MAINPID"
sysfcontent="$sysfcontent""\nExecReload=/bin/kill -s HUP \$MAINPID"
sysfcontent="$sysfcontent""\n[Install]"
sysfcontent="$sysfcontent""\nWantedBy=multi-user.target"
echo -e "$sysfcontent">/usr/lib/systemd/system/ssgo.service
if [ $? -ne 0 ]; then
    echo "intall systemctl fail"
    exit 1
fi
systemctl daemon-reload
systemctl start ssgo
if [ $? -ne 0 ]; then
    echo "start ssgo fail"
    exit 1
else
    echo "ssgo is successfully installed"
fi

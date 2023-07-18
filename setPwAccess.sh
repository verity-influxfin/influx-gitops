function removeAndCreateHtpasswdFile() {
    local local_htpasswd_file=$1
    if [ -f "$local_htpasswd_file" ]; then
        rm "$local_htpasswd_file"
    fi
    touch "$local_htpasswd_file"

}
function addHtpasswd() {
    local local_htpasswd_file=$1
    echo "influx_batman:\$apr1\$ucJwxK4H\$yS.MkOjJmGstpDt9OePZO1" >"$local_htpasswd_file"
}

function removeAndCreateHtaccessFile() {
    local local_htaccess_file=$1
    if [ -f "$local_htaccess_file" ]; then
        rm "$local_htaccess_file"
    fi
    touch "$local_htaccess_file"
}

function needCheckIP() {
    local local_htaccess_file=$1
    local accessIps="114.34.172.44, 13.112.224.83, 52.194.4.73, 18.179.183.180"
    echo "<Limit GET>
    order deny,allow
    deny from all
    allow from $accessIps
</Limit>" >>"$local_htaccess_file"
}

function needHtpasswd() {
    local local_target_dir=$1
    local local_htaccess_file=$2
    echo "AuthName \"PrivatePage\"
AuthType Basic
AuthUserFile \"$local_target_dir/private/.htpasswd\"
require valid-user" >>"$local_htaccess_file"
}

target_dir=$(pwd)

htpasswd_dir="$target_dir/private"

htpasswd_file="$htpasswd_dir/.htpasswd"
removeAndCreateHtpasswdFile "$htpasswd_file"
echo "$htpasswd_file"
addHtpasswd "$htpasswd_file"

htaccess_file="$doc_dir/.htaccess"
removeAndCreateHtaccessFile "$htaccess_file"

echo "Which env? [d]:dev, [p]:prod"
read env

if [ "$env" = "d" ]; then
    echo "development"
    needHtpasswd "$target_dir" "$htaccess_file"
else
    echo "production"
    needCheckIP "$htaccess_file"
    needHtpasswd "$target_dir" "$htaccess_file"
fi
exit 1

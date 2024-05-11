# This is the repo for a  network scanner system
This repo provides a web page that interacts with a scanner connecte  to Raspberry pi on the local network.
Through the webpage the  user can scan documents.
The scanned documents can  then be compressed to a zip file or convertedd to a pdf.

# Packages needed on Raspberry pi
1. Apache
2. PHP
3. imagemagick for the convert utility to create pdf

#Configure postfix using the instructions below
Step 1: Open a terminal and update your system's repo then upgrade.

 sudo apt-get update && sudo apt-get upgrade
 
Step 2: Install Postfix package.

 sudo apt-get install libsasl2-modules postfix

Select "Internet Site" when ask for "General type of mail configuration".

Step 3: Enter the hostname or fqdn of your raspberry pi.

Step 4: Generate a gmail app password for Postfix.
       
  Log in to your gmail account, then go to https://myaccount.google.com/security.
  Click Security.
  Scroll down and click 2-Step Verification. Sign in to your account and follow the steps to enable 2-Step Verification.
  Go to https://security.google.com/settings/security/apppasswords to generate app password.
  Select app Mail then on Select device click "other custom name" enter the name of your raspberry pi.
  then click generate.
  Copy the generated password and save it.
 
Step 5: On your terminal, create a file /etc/postfix/sasl/sasl_passwd and add password and username information.

   sudo vi /etc/postfix/sasl/sasl_passwd

   Enter the entry below.
   
   [smtp.gmail.com]:587 yourusername@gmail.com:yourapp_password

Step 6: Create the hash db file.

   sudo postmap /etc/postfix/sasl/sasl_passwd

Step 7: Change the permission of the sasl_passwd.db file.

  sudo chown root:root /etc/postfix/sasl/sasl_passwd /etc/postfix/sasl/sasl_passwd.db
  sudo chmod 0600 /etc/postfix/sasl/sasl_passwd /etc/postfix/sasl/sasl_passwd.db

Step 8: Configure the Postfix relay. Find the relayhost entry.
        Then enter the entry below.

    sudo vi /etc/postfix/main.cf

    relayhost = [smtp.gmail.com]:587

Step 9: Add the following entry at the end of the config file /etc/postfix/main.cf.

# Enable SASL authentication
smtp_sasl_auth_enable = yes
# Disallow methods that allow anonymous authentication
smtp_sasl_security_options = noanonymous
# Location of sasl_passwd
smtp_sasl_password_maps = hash:/etc/postfix/sasl/sasl_passwd
# Enable STARTTLS encryption
smtp_tls_security_level = encrypt
# Location of CA certificates
smtp_tls_CAfile = /etc/ssl/certs/ca-certificates.crt

ESC + :wq! + Enter

Step 10: Save the config file and restart the postfix service.
         Then test mail using postix.

     sudo systemctl restart postfix

sendmail -t youremailaddr@gmail.com < email.txt
FROM wordpress:latest

# install wp-cli
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN chmod +x wp-cli.phar
RUN mv wp-cli.phar /usr/local/bin/wp

# automate wp-cli installation, set site title, username, password, email
COPY install-wp.sh /usr/local/bin/install-wp
RUN chmod +x /usr/local/bin/install-wp

CMD [ "install-wp" ]
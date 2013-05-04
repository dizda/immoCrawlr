set :application, "immoCrawlr"
set :domain,      "root@immo.dizda.fr"
set :deploy_to,   "/opt/www/immo.dizda.fr"
set :app_path,    "app"
set :user,        "www-data"

set :scm,         :git
set :repository,  "file:///Users/high/Sites/immoCrawler"
# set :repository,  "#{domain}:/var/repos/#{application}.git"
set :deploy_via,    :copy


set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :use_sudo,      true
set  :keep_releases, 3


## Symfony2
set :shared_files,        ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor"]
set :update_vendors,      true
set :use_composer,        true
#set :composer_options,    "--verbose --prefer-dist"     #install script
set :update_vendors,      false
set :writable_dirs,       ["app/cache", "app/logs"]
set :webserver_user,      "www-data"
set :permission_method,   :chown
set :use_set_permissions, true
set :dump_assetic_assets, true


# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL
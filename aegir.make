; Aegir Provision makefile
;

core = 7.x
api = 2

; BOA-3.0.0-dev

projects[drupal][type] = "core"
projects[drupal][download][type] = "get"
projects[drupal][download][url] = "http://files.aegir.cc/core/drupal-7.41.1.tar.gz"

projects[hostmaster][type] = "profile"
projects[hostmaster][download][type] = "git"
projects[hostmaster][download][url] = "https://github.com/omega8cc/hostmaster.git"
projects[hostmaster][download][branch] = "feature/3.0.x-profile"

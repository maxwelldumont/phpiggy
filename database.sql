create table if not exists users (
  id bigint (20) unsigned NOT NULL AUTO_INCREMENT , 
  email varchar (255) NOT NULL, 
  password varchar (30) NOT NULL, 
  age tinyint (3) unsigned NOT NULL, 
  country varchar (255) NOT NULL, 
  social_media_url varchar (200), 
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP , 
  updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
  );
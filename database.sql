create table if not exists users (
  id bigint (20) unsigned NOT NULL AUTO_INCREMENT , 
  email varchar (255) NOT NULL, 
  password varchar (255) NOT NULL, 
  age tinyint (3) unsigned NOT NULL, 
  country varchar (255) NOT NULL, 
  social_media_url varchar (200), 
  created_at datetime DEFAULT NULL , 
  updated_at datetime DEFAULT NULL ,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
  );

create table if not exists transactions (
  id bigint (20) NOT NULL AUTO_INCREMENT , 
  description varchar (255) NOT NULL, 
  amount decimal (10,2) NOT NULL,
  date datetime NOT NULL,
  created_at datetime DEFAULT NULL , 
  updated_at datetime DEFAULT NULL ,
  user_id bigint (20) unsigned NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users (id)
  );

  CREATE TABLE IF NOT EXISTS receipts(
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  original_filename varchar(255) NOT NULL,
  storage_filename varchar(255) NOT NULL,
  media_type varchar(255) NOT NULL,
  transaction_id bigint(20) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY(transaction_id) REFERENCES transactions (id) ON DELETE CASCADE #record(s) will be removed upon deletion of the corresponding transaction
);

#test data:

REPLACE INTO `transactions` (`id`, `description`, `amount`, `date`, `created_at`, `updated_at`, `user_id`) 
VALUES (1, 'zd test transaction 1', 123.00, '2024-04-17 00:00:00', NOW(), NOW(), 2);

REPLACE INTO `transactions` (`id`, `description`, `amount`, `date`, `created_at`, `updated_at`, `user_id`) 
VALUES (2, 'zd test transaction 2', 345.00, '2024-04-18 00:00:00', NOW(), NOW(), 2);

REPLACE INTO `transactions` (`id`, `description`, `amount`, `date`, `created_at`, `updated_at`, `user_id`) 
VALUES (3, 'zd test transaction 3', 456.00, '2024-04-19 00:00:00', NOW(), NOW(), 2);

REPLACE INTO `transactions` (`id`, `description`, `amount`, `date`, `created_at`, `updated_at`, `user_id`) 
VALUES (4, 'zd test transaction 4', 567.00, '2024-04-19 00:00:00', NOW(), NOW(), 2);
create table users
(
  user_id INTEGER NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  user_name VARCHAR(100) NOT NULL,
  user_password VARCHAR(100) NOT NULL,
  display_name VARCHAR(100) NOT NULL,
  PRIMARY KEY ( user_id )
) ENGINE=INNODB;

create table contacts
(
  contact_1 INTEGER NOT NULL REFERENCES users(user_id),
  contact_2 INTEGER NOT NULL REFERENCES users(user_id),
  category INT NOT NULL,
  FOREIGN KEY (contact_1) REFERENCES users(user_id),
  FOREIGN KEY (contact_2) REFERENCES users(user_id),
  primary key(contact_1, contact_2)
) ENGINE=INNODB;

create table media
(
  media_id INTEGER NOT NULL AUTO_INCREMENT,
  uploader INTEGER NOT NULL REFERENCES users(user_id),
  title VARCHAR(100) NOT NULL,
  media_description VARCHAR(255) NOT NULL,
  media_type VARCHAR(10) NOT NULL,
  extension VARCHAR(10) NOT NULL,
  share_type TINYINT NOT NULL,
  discussion_type TINYINT NOT NULL,
  rating_type TINYINT NOT NULL,
  view_count INTEGER NOT NULL,
  file_path VARCHAR(2048) NOT NULL,
  file_size INTEGER NOT NULL,
  upload_time DATETIME,
  FOREIGN KEY (uploader) REFERENCES users(user_id),
  PRIMARY KEY ( media_id )
) ENGINE=INNODB;

create table media_categories
(
  media_id INTEGER NOT NULL REFERENCES media(media_id),
  category TINYINT NOT NULL,
  FOREIGN KEY (media_id) REFERENCES media(media_id) ON DELETE CASCADE
) ENGINE=INNODB;

create table media_keywords
(
  media_id INTEGER NOT NULL REFERENCES media(media_id),
  keyword VARCHAR(100) NOT NULL,
  FOREIGN KEY (media_id) REFERENCES media(media_id) ON DELETE CASCADE
) ENGINE=INNODB;

create table media_blocks
(
  media_id INTEGER NOT NULL REFERENCES media(media_id),
  user_id INTEGER NOT NULL REFERENCES users(user_id),
  FOREIGN KEY (media_id) REFERENCES media(media_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=INNODB;

create table media_ratings
(
  media_id INTEGER NOT NULL REFERENCES media(media_id),
  user_id INTEGER NOT NULL REFERENCES users(user_id),
  rating TINYINT NOT NULL,
  FOREIGN KEY (media_id) REFERENCES media(media_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=INNODB;

create table subscriptions
(
  contact_1 INTEGER NOT NULL REFERENCES users(user_id),
  contact_2 INTEGER NOT NULL REFERENCES users(user_id),
  FOREIGN KEY (contact_1) REFERENCES users(user_id),
  FOREIGN KEY (contact_2) REFERENCES users(user_id),
  primary key(contact_1, contact_2)
) ENGINE=INNODB;

create table conversations
(
  conv_id INTEGER NOT NULL AUTO_INCREMENT,
  conv_name VARCHAR(100) NOT NULL,
  conv_type TINYINT NOT NULL,
  primary key(conv_id)
) ENGINE=INNODB;

create table comments
(
  user_id INTEGER NOT NULL REFERENCES users(user_id),
  conv_id INTEGER NOT NULL REFERENCES conversations(conv_id),
  write_time DATETIME NOT NULL,
  comment VARCHAR(255) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (conv_id) REFERENCES conversations(conv_id),
  primary key(user_id, write_time)
) ENGINE=INNODB;

create table groups
(
  group_id INTEGER NOT NULL AUTO_INCREMENT,
  group_name VARCHAR(100) NOT NULL,
  primary key(group_id)
) ENGINE=INNODB;

create table group_conversations
(
  conv_id INTEGER NOT NULL REFERENCES conversations(conv_id),
  group_id INTEGER NOT NULL REFERENCES groups(group_id),
  FOREIGN KEY (conv_id) REFERENCES conversations(conv_id),
  FOREIGN KEY (group_id) REFERENCES groups(group_id),
  primary key(conv_id, group_id)
) ENGINE=INNODB;

create table comment_section
(
  media_id INTEGER NOT NULL REFERENCES media(media_id),
  conv_id INTEGER NOT NULL REFERENCES conversations(conv_id),
  FOREIGN KEY (media_id) REFERENCES media(media_id),
  FOREIGN KEY (conv_id) REFERENCES conversations(conv_id),
  primary key (media_id, conv_id)
) ENGINE=INNODB;

create table group_members
(
  group_id INTEGER NOT NULL REFERENCES groups(group_id),
  user_id INTEGER NOT NULL REFERENCES users(user_id),
  FOREIGN KEY (group_id) REFERENCES groups(group_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  primary key(group_id,user_id)
) ENGINE=INNODB;

create table messages
(
  contact_1 INTEGER NOT NULL REFERENCES users(user_id),
  contact_2 INTEGER NOT NULL REFERENCES users(user_id),
  conv_id INTEGER NOT NULL REFERENCES conversations(conv_id),
  FOREIGN KEY (contact_1) REFERENCES users(user_id),
  FOREIGN KEY (contact_2) REFERENCES users(user_id),
  FOREIGN KEY (conv_id) REFERENCES conversations(conv_id),
  primary key (contact_1, contact_2)
) ENGINE=INNODB;

create table playlists
(
  playlist_id INTEGER NOT NULL AUTO_INCREMENT,
  user_id INTEGER NOT NULL REFERENCES users(user_id),
  playlist_name VARCHAR(100) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  primary key (playlist_id)
) ENGINE=INNODB;

create table playlist_media
(
  playlist_id INTEGER NOT NULL REFERENCES playlists(playlist_id),
  media_id INTEGER NOT NULL REFERENCES media(media_id),
  FOREIGN KEY (playlist_id) REFERENCES playlists(playlist_id) ON DELETE CASCADE,
  FOREIGN KEY (media_id) REFERENCES media(media_id) ON DELETE CASCADE,
  primary key (playlist_id, media_id)
) ENGINE=INNODB;

create table favorite
(
  favorite_id INTEGER NOT NULL AUTO_INCREMENT,
  user_id INTEGER NOT NULL REFERENCES users(user_id),
  favorite_name VARCHAR(100) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  primary key (favorite_id)
) ENGINE=INNODB;

create table favorite_media
(
  favorite_id INTEGER NOT NULL REFERENCES favorite(favorite_id),
  media_id INTEGER NOT NULL REFERENCES media(media_id),
  FOREIGN KEY (favorite_id) REFERENCES favorite(favorite_id) ON DELETE CASCADE,
  primary KEY (favorite_id, media_id)
) ENGINE=INNODB;

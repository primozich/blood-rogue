drop table if exists m_monsters;
drop table if exists m_current_levels;
drop table if exists m_avatars;
drop table if exists m_classes;
drop table if exists m_item_types;

create table m_monsters
(
	id			varchar(36) primary key,
	name		varchar(36) not null,
	attacks		int not null,
	min_dmg		int not null,
	max_dmg		int not null,
	hit_points	int not null,
	letter		varchar(1) not null,
	xp			int not null,
	first_level	int not null,
	last_level	int not null,
	hit_chance	int not null,
	abilities	varchar(72) null,
	created		datetime not null,
	updated		datetime not null
) ENGINE=MyISAM;

create table m_current_levels
(
	id			int primary key auto_increment,
	avatar_id	varchar(36) not null,
	room_id		int not null,
	position_id	int not null,
	level		int not null,
	created		datetime not null,
	updated		datetime not null
) ENGINE=MyISAM;

create table m_avatars
(
	id			varchar(36) primary key,
	user_id		varchar(36) not null,
	status_type_id	int not null default 1,
	class_id	int not null default 1,
	name		varchar(36) not null,
	xp			int not null default 0,
	gold		int not null default 0,
	strength	int not null default 0,
	strength_max	int not null default 0,
	hit_points	int not null default 0,
	hit_points_max	int not null default 0,
	hunger		int not null default 0,
	pack_size	int not null default 0,
	level		int not null default 0,
	bookmark_id	int not null default 0,
	created		datetime not null,
	updated		datetime not null
) ENGINE=MyISAM;

create table m_classes
(
	id			int primary key auto_increment,
	name		varchar(36),
	description	varchar(256)
) ENGINE=MyISAM;

create table m_item_types
(
	id			int primary key auto_increment,
	name		varchar(36)
) ENGINE=MyISAM;
insert into m_item_types (id, name) values (1, 'Weapon');
insert into m_item_types (id, name) values (2, 'Armor');
insert into m_item_types (id, name) values (3, 'Potion');
insert into m_item_types (id, name) values (4, 'Scroll');
insert into m_item_types (id, name) values (5, 'Food');

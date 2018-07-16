drop table if exists m_stream_posts;
drop table if exists m_stream_post_bonuses;
drop table if exists m_users_stream_post_bonuses;
drop table if exists m_users_collection_items;
drop table if exists m_collections;
drop table if exists m_products;
drop table if exists m_product_types;
drop table if exists m_debug;
drop table if exists m_activity_types;
drop table if exists m_white_list;
drop table if exists m_games;
drop table if exists m_episodes;
drop table if exists m_cron_jobs;
drop table if exists m_users_products;
drop table if exists m_user_types;
drop table if exists m_avatars_activities;
drop table if exists m_avatars_zones;
drop table if exists m_avatars_items;
drop table if exists m_avatars_consumables;
drop table if exists m_users_achievements;
drop table if exists m_users_friends;
drop table if exists m_users;
drop table if exists m_zones;
drop table if exists m_activities;
drop table if exists m_items;
drop table if exists m_consumables;
drop table if exists m_activities_items;
drop table if exists m_activity_item_types;
drop table if exists m_item_prototypes;
drop table if exists m_item_protosubtypes;
drop table if exists m_achievements;
drop table if exists m_achievement_types;
drop table if exists m_users_achievements;
drop table if exists m_users_achievements_points;
drop table if exists m_levels;
drop table if exists m_status_types;
drop table if exists m_gifts;
drop table if exists m_invites;
drop table if exists m_payment_types;
drop table if exists m_reward_types;
drop table if exists m_payments;
drop table if exists m_fights;
drop table if exists m_fights_rounds;
drop table if exists m_fight_types;

create table m_cron_jobs
(
	id              int primary key auto_increment,
	name			varchar(32) not null,
	first_run		bigint(20) not null default 0,
	last_run		bigint(20) not null default 0
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_cron_jobs values (1, 'Energy', 0, 0);
insert into m_cron_jobs values (2, 'Show Times', 0, 0);

create table m_games
(
	id				varchar(36) primary key,
	name			varchar(36) not null,
	fb_api_key		varchar(32) not null,
	fb_secret		varchar(32) not null,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_games (id,name,fb_api_key,fb_secret,created,updated) values ('3ec5357c-b11c-11df-961f-ce6db64b54c2', 'Blood Rogue', 'b4bf86d4e92109d6c29573d32ddce548', 'cd0149ba2aebfc650dfbbed9b231aa39', now(), now());

create table m_episodes
(
	id				varchar(36) primary key,
	name			varchar(36) not null,
	game_id			varchar(36) not null,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_achievements
(
	id				varchar(36) primary key,
	achievement_type_id	int not null,
	tier			int not null default 1,
	thumb_image		varchar(32) null,
	points_required	int not null default 1,
	cash_award		int not null default 0,
	xp_award		int not null default 0,
	energy_award	int not null default 0,
	points_award	int not null default 0,
	currency_award	int not null default 0,
	product_award	varchar(36) null,
	item_award		varchar(36) null,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_users
(
	id				varchar(36) primary key,
	facebook_id		bigint(20) default null,
	sex				enum('male', 'female') default null,
	user_type_id	int not null default 1,
	status_type_id	int not null default 1,
	ip_address		varchar(16) default null,
	name			varchar(24) default null,
	avatar_name		varchar(64) default null,
	email			varchar(36) default null,
	real_money_currency	int not null default 0,
	game_only_currency int not null default 0,
	energy			int not null default 0,
	energy_max		int not null default 0,
	energy_refill	bigint(20) not null default 0,
	stamina			int not null default 0,
	stamina_max	int not null default 0,
	stamina_refill	bigint(20) not null default 0,
	xp				int not null default 0,
	level			int not null default 0,
	friend_count	int not null default 0,
	bookmark_id		int not null default 0,
	current_zone_id	int not null default 0,
	current_activity_id	varchar(36) null,
	karma			int not null default 0,
	sneaky			int not null default 0,
	skill_points	int not null default 0,
	login_streak	int not null default 0,
	last_login		datetime not null,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
create index facebook_id_index on m_users (facebook_id);

create table m_users_friends
(
	id				int primary key auto_increment,
	user_id			varchar(36) not null,
	friend_id		varchar(36) null,
	friend_fb_id	bigint(20) not null,
	status_type_id	int not null default 4,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_invites
(
	id				int primary key auto_increment,
	user_id			varchar(36) not null,
	friend_id		varchar(36) null,
	friend_fb_id	bigint(20) not null,
	status_type_id	int not null default 4,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_activities_items
(
	id				int primary key auto_increment,
	activity_id		varchar(36) not null,
	item_id			varchar(36) not null,
	activity_item_type_id		int not null,
	number_required	int not null default 0,
	drop_chance		int not null default 100,
	reward_level	int default null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_activity_item_types
(
	id              int primary key auto_increment,
	name			varchar(64) not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_items
(
	id              varchar(36) primary key,
	item_type_id	int not null,
	item_sub_type_id	int not null,
	stat_strength	int not null default 0,
	stat_quickness	int not null default 0,
	stat_intelligence		int not null default 0,
	name			varchar(64) not null,
	description		varchar(256) not null,
	ascii			varchar(32) not null,
	sell_in_store	tinyint(2) not null default 0,
	cost			decimal(8,2) not null,
	min_level		int not null default 1,
	min_zone		int not null default 1,
	collection_id	int not null default 0,
	can_gift		tinyint(2) not null default 0,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_consumables
(
	id              varchar(36) primary key,
	consumable_type_id	int not null,
	consumable_sub_type_id	int not null,
	name			varchar(64) not null,
	description		varchar(256) not null,
	duration		int not null default 0,
	ascii			varchar(32) not null,
	sell_in_store	tinyint(2) not null default 0,
	cost			decimal(8,2) not null,
	min_level		int not null default 1,
	min_zone		int not null default 1,
	collection_id	int not null default 0,
	can_gift		tinyint(2) not null default 0,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_item_prototypes
(
	id              int primary key auto_increment,
	name			varchar(64) default null,
	desription		varchar(128) default null,
	ascii			varchar(32) not null,
	min_level		int not null default 1,
	max_level		int not null default 10000,
	min_zone		int not null default 1,
	min_damage		int not null default 0,
	max_damage		int not null default 0,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_item_protosubtypes
(
	id              int primary key auto_increment,
	item_prototype_id		int not null,
	name			varchar(64) default null,
	desription		varchar(128) default null,
	ascii			varchar(32) not null,
	min_level		int not null default 1,
	max_level		int not null default 10000,
	min_zone		int not null default 1,
	min_damage		int not null default 0,
	max_damage		int not null default 0,
	bonus_damage	int not null default 0,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_zones
(
	id              int primary key auto_increment,
	name			varchar(64) not null,
	description		varchar(256) not null,
	title			varchar(32) not null,
	banner_image	varchar(32) not null,
	activity_to_unlock	varchar(36) default null,
	sort_order		int not null,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_activities
(
	id              varchar(36) primary key,
	activity_type_id              int not null default 1,
	name			varchar(128) not null,
	description 	varchar(256) not null,
	result_text		varchar(128) not null,
	energy_req		int not null default 0,
	level_req		int not null default 0,
	friends_req		int not null default 0,
	thumb_image		varchar(32) not null,
	xp				int not null default 0,
	cash			int not null default 0,
	mastery_per		int not null default 100,
	zone_id			int not null default -1,
	sort_order		int not null default 0,
	is_gateway		tinyint(2) not null default 0,
	next_zone_id	int not null default 0,
	npc_id			int not null default 0,
	created         datetime not null,
	updated         datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_avatars_items
(
	id				int primary key auto_increment,
	avatar_id		varchar(36) not null,
	magic_type_id	int not null default 0,
	modifier		int not null default 0,
	item_type_id	int not null,
	item_sub_type_id	int not null,
	is_saved		tinyint(2) not null default 0,
	created			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_avatars_consumables
(
	id				int primary key auto_increment,
	avatar_id		varchar(36) not null,
	consumable_id	varchar(36) not null,
	is_saved		tinyint(2) not null default 0,
	date_used		datetime null,
	created			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_gifts
(
	id					varchar(36) primary key,
	sender_id			varchar(36) not null,
	recipient_id		varchar(36) null,
	recipient_fb_id		bigint null,
	item_id				varchar(36) not null,
	status_type_id		int not null,
	message				varchar(128) null,
	created				datetime not null,
	accepted			datetime null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_avatars_zones
(
	id				int primary key auto_increment,
	avatar_id		varchar(36) not null,
	zone_id			int not null default 1,
	finished		datetime null,
	bonus_received	datetime null,
	created			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_avatars_activities
(
	id				int primary key auto_increment,
	avatar_id		varchar(36) not null,
	activity_id		varchar(36) not null,
	mastery_points	int not null default 0
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_users_achievements
(
	id				int primary key auto_increment,
	user_id			varchar(36) not null,
	achievement_id	varchar(36) not null,
	points			int not null default 0,
	date_achieved	datetime default null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
CREATE INDEX user_id_index ON m_users_achievements (user_id);

create table m_users_achievements_points
(
	id				int primary key auto_increment,
	user_id			varchar(36) not null,
	achievement_type_id	int not null,
	points			int not null default 0,
	created			datetime default null,
	updated			datetime default null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_activity_types
(
	id					int primary key auto_increment,
	name				varchar(32)
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_activity_types (id, name) values (1, 'story');
insert into m_activity_types (id, name) values (2, 'ride');
insert into m_activity_types (id, name) values (3, 'bonus');
insert into m_activity_types (id, name) values (4, 'special');

create table m_status_types
(
	id					int primary key auto_increment,
	name				varchar(32)
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_status_types values (1, 'active');
insert into m_status_types values (2, 'archived');
insert into m_status_types values (3, 'suspended');
insert into m_status_types values (4, 'pending');
insert into m_status_types values (5, 'removed');
insert into m_status_types values (6, 'canceled');
insert into m_status_types values (7, 'accepted');

create table m_achievement_types
(
	id					int primary key auto_increment,
	name				varchar(32),
	description			varchar(256),
	thumb_image			varchar(32),
	sort_order			int not null,
	created				datetime not null,
	updated				datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_achievement_types values (1, 'Overall', 'Total feeds from the beginning of time', 'fish_bowl.png', 1, now(), now());
insert into m_achievement_types values (2, 'This Week', 'A numeric representation of the amount of love shown this week', 'arrow.png', 2, now(), now());
insert into m_achievement_types values (3, 'Last Week', 'A numeric represenation of last week''s love', 'arrow.png', 3, now(), now());

create table m_levels
(
	id					int primary key auto_increment,
	xp					int not null default 0,
	energy				int not null default 2
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_payments
(
	id				int primary key auto_increment,
	user_id			varchar(36) not null,
	payment_type_id	int not null,
	real_money_currency	int not null,
	amount			decimal(6,2) null,
	order_number	varchar(64),
	created			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_payment_types
(
	id              int primary key auto_increment,
	payment_type	varchar(36) not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_payment_types (id, payment_type) value (1, 'Paypal');
insert into m_payment_types (id, payment_type) value (2, 'Trialpay');
insert into m_payment_types (id, payment_type) value (3, 'Credit Card');
insert into m_payment_types (id, payment_type) value (4, 'Spare Change');
insert into m_payment_types (id, payment_type) value (5, 'Offerpal');
insert into m_payment_types (id, payment_type) value (6, 'Super Rewards');

create table m_debug
(
	id				int primary key auto_increment,
	debug			text,
	created			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_white_list
(
	id				int primary key auto_increment,
	email			varchar(36) null,
	code			varchar(36) null,
	date_used		datetime null,
	user_type_id	int not null default 0,
	status_type_id	int not null default 0,
	created			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_products
(
	id				varchar(36) primary key,
	reward_type_id	int not null,
	reward_data		varchar(256),
	name			varchar(128) not null,
	description		varchar(512) not null,
	cost			int not null,
	image			varchar(32) null,
	sort_order		int not null,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_users_products
(
	id				int primary key auto_increment,
	user_id			varchar(36) not null,
	product_id		varchar(36) not null,
	cost			int not null,
	currency_type_id int not null default 1,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_product_types
(
	id				int primary key auto_increment,
	product_type	varchar(36) not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_product_types (id, product_type) value (1, 'Energy Recharge');
insert into m_product_types (id, product_type) value (2, '3 Show Times');
insert into m_product_types (id, product_type) value (3, 'Item');

create table m_user_types
(
	id				int primary key auto_increment,
	user_type		varchar(36) not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_user_types (id, user_type) value (1, 'player');
insert into m_user_types (id, user_type) value (2, 'admin');

create table m_collections
(
	id				int primary key auto_increment,
	reward_type_id	int not null default 1,
	reward_data		varchar(256) null,
	name			varchar(36) not null,
	description		varchar(256) null,
	sort_order		int not null,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_collections (id, reward_type_id, reward_data, name, description, sort_order, created, updated) values (1, 12, '0e250e1c-518e-11df-90cd-c1f484b80f22', 'Balloon Art', 'The Big Wheel - biggest ferris wheel this side of the Mississippi', 5, now(), now());

create table m_users_collection_items
(
	id				int primary key auto_increment,
	user_id			varchar(36) not null,
	item_id			varchar(36)	not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_reward_types
(
	id				int primary key auto_increment,
	reward_type	varchar(36) not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_reward_types (id, reward_type) value (1, 'Vanity');
insert into m_reward_types (id, reward_type) value (2, 'XP');
insert into m_reward_types (id, reward_type) value (3, 'Cash');
insert into m_reward_types (id, reward_type) value (4, 'Energy Refill');
insert into m_reward_types (id, reward_type) value (5, 'Max Energy Bonus +1');
insert into m_reward_types (id, reward_type) value (6, 'Max Energy Bonus +2');
insert into m_reward_types (id, reward_type) value (7, 'Max Energy Bonus +3');
insert into m_reward_types (id, reward_type) value (8, 'Max Energy Bonus +5');
insert into m_reward_types (id, reward_type) value (9, 'Max Energy Bonus +8');
insert into m_reward_types (id, reward_type) value (10, 'Max Energy Bonus +10');
insert into m_reward_types (id, reward_type) value (11, 'Zone');
insert into m_reward_types (id, reward_type) value (12, 'Item');
insert into m_reward_types (id, reward_type) value (13, 'Random Item');
insert into m_reward_types (id, reward_type) value (14, '3 Show Times');

create table m_stream_posts
(
	id				int primary key auto_increment,
	stream_post_type_id	int not null,
	stream_post_sub_type_id int null,
	post_title		varchar(64) not null,
	post_title_link	varchar(128) not null,
	post_description	varchar(256) not null,
	stream_text		varchar(64) not null,
	image			varchar(32) not null,
	action_text		varchar(32) not null,
	action_link		varchar(128) not null,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_stream_post_bonuses
(
	id				varchar(36) primary key,
	stream_post_type_id int not null,
	stream_post_sub_type_id int null,
	user_id			varchar(36) not null,
	game_data		varchar(36) null,
	reward_type_id	int not null,
	reward_data		varchar(256) not null,
	number_available int not null,
	number_claimed	int not null default 0,
	created			datetime not null,
	updated			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_users_stream_post_bonuses
(
	id				int primary key auto_increment,
	user_id			varchar(36) not null,
	bonus_id		varchar(36) not null,
	created			datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;

create table m_fights
(
	id              varchar(36) primary key,
	fight_type_id   int not null,
	attacker_id     varchar(36),
	attacker_level  int not null,
	attacker_crowd  int not null default 0,
	attacker_act_count int not null,
	attacker_rounds int not null default 0,
	defender_id     varchar(36),
	defender_level  int not null,
	defender_act_count int not null,
	defender_crowd  int not null default 0,
	defender_rounds int not null default 0,
	winner_id       varchar(36),
	winner_score    int not null default 0,
	winner_xp       int not null default 0,
	winner_cash     int not null default 0,
	loser_id        varchar(36),
	loser_score     int not null default 0,
	loser_xp        int not null default 0,
	loser_cash      int not null default 0,
	rand_seed       decimal(13,1) not null default 0,
	created         datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
CREATE INDEX attacker_id_index ON m_fights (attacker_id);
CREATE INDEX defender_id_index ON m_fights (defender_id);

create table m_fights_rounds
(
	id                  int primary key auto_increment,
	fight_id            varchar(36) not null,
	round_id            int not null,
	attacker_item_id    varchar(36) default null,
	defender_item_id    varchar(36) default null,
	round_winner_id     varchar(36) not null,
	is_crowd_favorite   tinyint(2) not null default 0,
	created             datetime not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
CREATE INDEX fight_id_index ON m_fights_rounds (fight_id);

create table m_fight_types
(
	id          int primary key auto_increment,
	name        varchar(32) not null
) ENGINE=MyIsam DEFAULT CHARSET=utf8;
insert into m_fight_types (id,name) values (1, 'Big Top Battle');

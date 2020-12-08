CREATE TABLE tx_er24rechtstexte_domain_model_domainconfig (

	domain varchar(255) DEFAULT '' NOT NULL,
	api_key varchar(255) DEFAULT '' NOT NULL,
	imprint_source int(11) DEFAULT '0' NOT NULL,
	imprint_de text,
	imprint_de_tstamp int(11) DEFAULT '0' NOT NULL,
	imprint_en text,
	imprint_en_tstamp int(11) DEFAULT '0' NOT NULL,
	privacy_source int(11) DEFAULT '0' NOT NULL,
	privacy_de text,
	privacy_de_tstamp int(11) DEFAULT '0' NOT NULL,
	privacy_en text,
	privacy_en_tstamp int(11) DEFAULT '0' NOT NULL,
	social_source int(11) DEFAULT '0' NOT NULL,
	social_de text,
	social_de_tstamp int(11) DEFAULT '0' NOT NULL,
	social_en text,
	social_en_tstamp int(11) DEFAULT '0' NOT NULL,
	analytics_id varchar(255) DEFAULT '' NOT NULL,
	flag_embed_tracking smallint(5) unsigned DEFAULT '0' NOT NULL,
	flag_user_centrics_embed smallint(5) unsigned DEFAULT '0' NOT NULL,
	flag_opt_out_code smallint(5) unsigned DEFAULT '0' NOT NULL,
	root_pid int(11) DEFAULT '0' NOT NULL,
	site_config_name varchar(255) DEFAULT '' NOT NULL

);

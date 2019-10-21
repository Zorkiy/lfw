ALTER TABLE system_arch_clients RENAME powercounter_arch_clients;
ALTER TABLE system_arch_clients_month RENAME powercounter_arch_clients_month;
ALTER TABLE system_arch_clients_week RENAME powercounter_arch_clients_week;
ALTER TABLE system_arch_deep RENAME powercounter_arch_deep;
ALTER TABLE system_arch_deep_month RENAME powercounter_arch_deep_month;
ALTER TABLE system_arch_deep_week RENAME powercounter_arch_deep_week;
ALTER TABLE system_arch_enterpoint RENAME powercounter_arch_enterpoint;
ALTER TABLE system_arch_enterpoint_month RENAME powercounter_arch_enterpoint_month;
ALTER TABLE system_arch_enterpoint_week RENAME powercounter_arch_enterpoint_week;
ALTER TABLE system_arch_hits RENAME powercounter_arch_hits;
ALTER TABLE system_arch_hits_month RENAME powercounter_arch_hits_month;
ALTER TABLE system_arch_hits_week RENAME powercounter_arch_hits_week;
ALTER TABLE system_arch_ip RENAME powercounter_arch_ip;
ALTER TABLE system_arch_ip_month RENAME powercounter_arch_ip_month;
ALTER TABLE system_arch_ip_week RENAME powercounter_arch_ip_week;
ALTER TABLE system_arch_num_searchquery RENAME powercounter_arch_num_searchquery;
ALTER TABLE system_arch_num_searchquery_week RENAME powercounter_arch_num_searchquery_week;
ALTER TABLE system_arch_num_searchquery_month RENAME powercounter_arch_num_searchquery_month;
ALTER TABLE system_arch_refferer RENAME powercounter_arch_refferer;
ALTER TABLE system_arch_refferer_month RENAME powercounter_arch_refferer_month;
ALTER TABLE system_arch_refferer_week RENAME powercounter_arch_refferer_week;
ALTER TABLE system_arch_robots RENAME powercounter_arch_robots;
ALTER TABLE system_arch_robots_month RENAME powercounter_arch_robots_month;
ALTER TABLE system_arch_robots_week RENAME powercounter_arch_robots_week;
ALTER TABLE system_arch_searchquery RENAME powercounter_arch_searchquery;
ALTER TABLE system_arch_searchquery_month RENAME powercounter_arch_searchquery_month;
ALTER TABLE system_arch_searchquery_week RENAME powercounter_arch_searchquery_week;
ALTER TABLE system_arch_time RENAME powercounter_arch_time;
ALTER TABLE system_arch_time_month RENAME powercounter_arch_time_month;
ALTER TABLE system_arch_time_temp RENAME powercounter_arch_time_temp;
ALTER TABLE system_arch_time_week RENAME powercounter_arch_time_week;
ALTER TABLE system_cities RENAME powercounter_cities;
ALTER TABLE system_ip_compact RENAME powercounter_ip_compact;
ALTER TABLE system_regions RENAME powercounter_regions;
ALTER TABLE system_ip RENAME powercounter_ip;
ALTER TABLE system_pages RENAME powercounter_pages;
ALTER TABLE system_refferer RENAME powercounter_refferer;
ALTER TABLE system_searchquerys RENAME powercounter_searchquerys;
ALTER TABLE system_thits RENAME powercounter_thits;

ALTER TABLE powercounter_arch_clients CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_clients_month CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_deep CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_deep_month CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_enterpoint CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_enterpoint_month CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_hits CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_hits_month CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_ip CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_ip_month CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_num_searchquery CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_num_searchquery_month CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_refferer CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_refferer_month CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_robots CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_robots_month CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_searchquery CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_searchquery_month CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_time CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_time_month CHANGE putdate putdate DATE NOT NULL DEFAULT '0000-00-00';

ALTER TABLE powercounter_arch_hits_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_clients_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_deep_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_enterpoint_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_hits_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_ip_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_num_searchquery_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_refferer_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_robots_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_searchquery_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE powercounter_arch_time_week CHANGE putdate_begin putdate_begin DATE NOT NULL DEFAULT '0000-00-00',
CHANGE putdate_end putdate_end DATE NOT NULL DEFAULT '0000-00-00';
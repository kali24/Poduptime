CREATE TABLE pods (
 id serial8 UNIQUE PRIMARY KEY,
 domain text UNIQUE NOT NULL,
 name text,
 softwarename text,
 masterversion text,
 shortversion text,
 score int DEFAULT 10,
 weightedscore numeric(5,2) DEFAULT 10,
 adminrating decimal DEFAULT 10,
 userrating decimal DEFAULT 10,
 hidden boolean DEFAULT true,
 ip text,
 country text,
 city text,
 state text, 
 lat text,
 long text,
 email text,
 ipv6 boolean,
 secure boolean,
 sslvalid text,
 monthsmonitored int,
 signup boolean,
 total_users int, 
 active_users_halfyear int,
 active_users_monthly int,
 local_posts int,
 uptime_alltime numeric(5,2),
 status text,
 responsetime text,
 service_facebook boolean,
 service_twitter boolean,
 service_tumblr boolean,
 service_wordpress boolean,
 service_xmpp boolean,
 token text,
 publickey text,
 tokenexpire timestamp,
 terms text,
 sslexpire timestamp,
 dnssec boolean,
 comment_counts int,
 weight int DEFAULT 50,
 date_updated timestamp DEFAULT current_timestamp,
 date_laststats timestamp DEFAULT current_timestamp,
 date_created timestamp DEFAULT current_timestamp
);
CREATE TABLE rating_comments (
 id serial8 UNIQUE PRIMARY KEY,
 domain text NOT NULL,
 comment text,
 admin text, 
 pod_id int,
 rating int,
 username text,
 userurl text,
 date_created timestamp DEFAULT current_timestamp
);
CREATE TABLE apikeys (
 key text,
 email text,
 usage int,
 date_created timestamp DEFAULT current_timestamp
);

CREATE TABLE clicks (
 domain text,
 manualclick int,
 autoclick int,
 date_clicked timestamp DEFAULT current_timestamp
);

CREATE TABLE checks (
 domain text,
 online boolean,
 error text,
 ttl numeric(8,6),
 total_users int,
 local_posts int,
 comment_counts int,
 shortversion text,
 date_checked timestamp DEFAULT current_timestamp
);

CREATE TABLE masterversions (
 software text,
 version text,
 date_checked timestamp DEFAULT current_timestamp
);


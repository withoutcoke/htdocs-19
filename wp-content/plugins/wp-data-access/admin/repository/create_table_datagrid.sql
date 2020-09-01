CREATE TABLE {wp_prefix}wpda_datagrid{wpda_postfix} (
  grid_id                          mediumint(9)  NOT NULL AUTO_INCREMENT,
  grid_name                        varchar(100)  NOT NULL,
  grid_schema_name                 varchar(64)   NOT NULL,
  grid_table_name                  varchar(64)   NOT NULL,
  PRIMARY KEY (grid_id),
  UNIQUE KEY (grid_name)
);
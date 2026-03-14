# datasikkerhet_i_utvikling_og_drift_v26


## Steg2

### Update to V2-database

Run these 2 commands to create and seed the steg2_datasikkerhet database:
`docker exec -i datasikkerhet_db mysql -pdev < sql/steg2/init.sql`
*Creates the db*
`docker exec -i datasikkerhet_db mysql -pdev < sql/steg2/seed.sql`
*Seeds the db*

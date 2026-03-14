# datasikkerhet_i_utvikling_og_drift_v26


## Steg2

Changes from steg1 to steg2

### Database

#### Update to V2-database

Run these 2 commands to create and seed the steg2_datasikkerhet database:
```
docker exec -i datasikkerhet_db mysql -pdev < sql/steg2/init.sql
```
*Creates the db*
```
docker exec -i datasikkerhet_db mysql -pdev < sql/steg2/seed.sql
```
*Seeds the db*

#### Changes to database

- Named all foreign keys
- Created new table for security questions, ensuring all users can have multiple security questions

### API

### Frontend

#### Created API Service class

- Replaced old API client for frontend with a new API service class
- Abtracting API logic behind specific functions provides ease of use and better configuration capabilities

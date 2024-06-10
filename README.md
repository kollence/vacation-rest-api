## Administrator

### Timovi
1. Lista timove GET /api/v1/teams Lista svih korisnika

2. Prikazuje tim GET /api/v1/teams/{team}

3. Kreira tim POST /api/v1/teams Obavezno ime tima i menadzer tima. Neobavezno dodavanje korisnika (naknadno se mogu dodavati, menjati, brisati)
    Request JSON
        {
            "name": "Tim Racunovodja",
            "manager_id": 1,
            "user_ids": [2,3]
        }
4. Abdejtuje tim PUT/PATCH /api/v1/teams/{team} Oba scenarija su pokrivena
   Za PATCH 
        {
            "name": "Novi tim PATCH"
            "user_ids": []
        }
   Za PUT
        {
            "name": "Novi tim PUT",
            "manager_id": 2,
            "user_ids": [3,4]
        }
5. Brise tim DELETE /api/v1/teams/{team}


6. Brise korisnika(e) iz tima POST /api/v1/teams/remove-team-user/{team}
    {
        "user_ids": [1,2,4]
    }

### Korisnici
1. Lista korisnika GET /api/v1/users Lista svih korisnika sa prikacenim ovlascenjima

2. Prikaz korisnika GET /api/v1/users/{user}

3. Kreira korisnika POST /api/v1/users Obavezno ime i prezime, neobavezno email, password, timovi (naknadno se mogu dodavati, menjati, brisati)
    Request JSON
        {
            "name": "Ime Prezime",
            "email": "ime.prezime@gmail.com",
            "password": "123456",
            "team_id": null,
        }








- [Simple, fast routing engine](https://laravel.com/docs/routing).


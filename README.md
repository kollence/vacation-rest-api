Na samo inicijalno logovanje korisnima se automatski dodeljuje rola user
Pokretanjem db:seed se kreiraju 5 korinika 3 menadzera 1 administrator. Sve stoji u DatabaseSeeder
- Administrator: Admin moze da kreira, menja, briše korisnike i dodeljuje uloge korisnika aplikacije. Kreira timove i dodeljuje korisnike timovima. Obeležava menadžera tima.
- Menadzer: Odgovara na zahteve korisnika za odmor unutar tima. Datumi odmora unutar tima ne smeju da se poklapaju. Ima pregled samo svog tima.
- Korisnik: Šalje zatev za odmor i može da vidi unutar tima sve koji su kreirali zahtev i čekaju da se odobri kao i sve zateve koji su sa statusom "odobren" ili "na čekanju". Vidi istoriju svojih zahteva i koliko mu je dana odmora i slobodnih dana preostalo.

### Timovi
1. Lista timove GET /api/v1/teams Lista svih korisnika

2. Prikazuje tim GET /api/v1/teams/{team}

3. Kreira tim POST /api/v1/teams Obavezno ime tima i makar jedan menadzer tima. Neobavezno dodavanje korisnika (naknadno se mogu dodavati, menjati, brisati)
 -  POST
  -   {
  -       "name": "Tim Racunovodja",
  -       "managers": [1],
  -       "regular_users": [2,3]
  -   }
4. Abdejtuje tim PUT/PATCH /api/v1/teams/{team} Oba scenarija su pokrivena
 -  PATCH 
  -   {
  -       "name": "Novi tim PATCH"
  -       "regular_users": []
  -   }
 -  PUT
  -   {
  -       "name": "Novi tim PUT",
  -       "managers": [1],
  -       "regular_users": [3,4]
  -   }
5. Brise tim DELETE /api/v1/teams/{team}


6. Brise korisnika(e) iz tima POST /api/v1/teams/remove-team-user/{team}
 -  POST
  -   {
  -     "regular_users": [1,2,4]
  -   }

### Korisnici
1. Lista korisnika GET /api/v1/users Lista svih korisnika sa prikacenim ovlascenjima

2. Prikaz korisnika GET /api/v1/users/{user}

3. Kreira korisnika POST /api/v1/users Obavezno ime, mail, password, password confirmation (role se naknadno mogu dodavati, menjati, brisati, DEFAULT je user)
 -  POST
  -    {
  -        "name": "Bora",
  -        "email": "bora@create.com",
  -        "password": "123123123",
  -        "password_confirmation": "123123123"
  -    }
4. Abdejtovanje korisnika PUT/PATCH /api/v1/users/{user} Role se mogu naknadno dodavati i menjati
   (zahteva jednostavniji pristup, bice zakucano na posedovanje samo jedne role)
 -  PATCH 
  -        {
  -            "name": "Novi korisnik PATCH"
  -        }
 -  PUT
  -        {
  -            "name": "Novi korisnik PUT",
  -            "email": "boza3@create.com",
  -           "password": "123123123",
  -            "password_confirmation": "123123123",
  -            "role_id": [2]
  -        }
5. Brise korisnika DELETE /api/v1/users/{user} automatski se brise i role_user red gde je korisnik pripadao



### Zahtevi za odmor ( Vacation Request )
1. Lista VR GET /api/v1/vacation-request Lista svih VR

2. Prikaz VR GET /api/v1/vacation-request/{vacationRequest}

3. Istorija VR GET /api/v1/vacation-request/show-history prikazueje sve VR koje je kreirao auth korisnik

4. Kreira VR POST /api/v1/vacation-request/show-history start_date & end_date obavezni
     (datumi se ne mogu kreirati u proslosti i ne mogu se preklapati sa approved, start_date ne moze biti veci od end_date)
 -  POST
  -    {
  -        "start_date": "2024-08-01",
  -        "end_date": "2024-08-09",
  -        "reason": "Family vacation"
  -    }
5. Abdejtovanje VR PUT/PATCH /api/v1/vacation-request/{vacationRequest}
 -  PATCH 
  -    {
  -        "end_date": "2024-08-05",
  -    }
 -  PUT
  -    {
  -        "start_date": "2024-08-01",
  -        "end_date": "2024-08-09",
  -        "reason": "Family vacation"
  -    }
6. Brise VR DELETE /api/v1/vacation-request/{vacationRequest}

7. Approve VR PATCH /api/v1/vacation-requests/9/approve Moze samo da urade Mendadzeri tima i tek kada odobri tada se vacation_days smanjuje u users tabeli
 -  PATCH 
  -    {
  -        "status": "approved" // or rejected 
  -    }
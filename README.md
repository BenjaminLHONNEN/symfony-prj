jeux_strat
====

A Symfony project who list games and allow to rate them. Who can find a list of the games with their notes and you can filter them by name or by their note review

==== <br>
SETUP : 

vagrant up <br>
vagrant ssh <br>
git clone https://github.com/BenjaminLHONNEN/jeux_strat.git <br>
make comp-install <br>
make fixtures <br>
make start <br>

====

User :  <br>
user@ynov.com <br>
1234 <br>
Admin : <br>
admin@ynov.com <br>
1234 <br>

API DOC :  <br>
*/doc/api  <br>

====

Member of the Project :  <br>

Emeric LESAULT <br>
Benjamin L'HONNEN <br>

====

Make Command :  <br><br>
make start: <br>
    run the server without log in the console

make stop:<br>
    stop the server

make run:<br>
    run the server with log in the console

make autoload-refresh:<br>
    dump the auto load

make create-bundle:<br>
    luch the program to add a bundle to the project

make comp-install:<br>
    run the composer install

make fixtures:<br>
    add user and games to the Data Base
    
====

To Do : <br>

 - redirect user to the good page when he sign in
 - add mobile version
 - add a relation of one to one between User and Rating
 - add search bar in the page list_game
 - add different display (table / mosaic) for the page list_game
 - create an API to get basic user info / game / rating

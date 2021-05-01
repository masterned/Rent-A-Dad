# Rent-A-Dad

## To Dr. Remshagen
In order to update the project path (root),
go to utils/Utils.php and change the constant `PROJECT_PATH`.

Also, please make sure your SQL database is up to date.
I had been running into a weird bug before I updated mine,
and updating it seems to have fixed it.

## Who are we?
Ever have a man in your life that captures the essence of what it means to be "dad"?

* Something broke, and he's able to somehow fix it?
* Dog needed a house, and he built it?
* He's probably the one who taught you sports, grilling, and hunting.

Everyone needs a good dad in their life.
Even if you already have an awesome dad, he can't always be there.
Even when he's not there, problems still come up, and a dad would be the best solution.

Why not rent one for a while?
Even if Dad is there, one dad is great, imagine having another!
Let Dad have a dad of his own.

We offer dads that:
* coach
* repair
* build
* tutor
* and so much more!

Need to give your kid that awkward talk? We have dads that can do that too.

Our dad's go through a meticulous vetting process to guarantee your dad for the day is someone you'd be happy to have as your dad.

We have a long list of dads to choose from and will set up an interview with you and the dad of your choice to be able to get to know him before he starts dad-ing.
We want to make sure your dad is a perfect match.

## TODO
* [x] db
    * [x] creation script
        * [x] tables
        * [x] data
        * [x] credentials
    * [x] utility class
    * [x] controller connection
    * [x] error message display
* [x] validator
    * [x] utility class
    * [x] controller connection
* [ ] pages
    * [ ] registration
        * [x] view
        * [x] validation
            * [x] patterns
            * [x] feedback
        * [x] model
        * [x] routing
        * [x] view & model communication (controller)
        * [x] session data
        * [ ] un-authorization
    * [ ] login
        * [x] view
        * [x] validation
            * [x] verification
            * [x] feedback
        * [x] model
        * [x] routing
        * [x] controller
        * [x] session data
        * [ ] un-authorization
    * [x] dad selection
        * [x] view
        * [x] model
        * [x] routing
        * [x] controller
    * [x] review rented dads
        * [x] view
        * [x] authorization
        * [x] model
        * [x] routing
        * [x] controller
    * [ ] set appointment
        * [x] view
        * [ ] authorization
        * [ ] model
        * [ ] routing
        * [ ] controller
* [x] security
    * [x] enforce https
    * [x] password encryption
* [x] path
* [x] session
    * [x] start on load
    * [x] clear on logout
* [ ] functions properly in any subfolder of htdocs
* [x] site-wide style

## Stretch Goals
* [x] personalized greeting
* [ ] 401 Unauthorized
* [ ] 418 I'm a teapot
* [ ] 500 Internal Server Error
* [ ] filter dads by skill
* [ ] client phone #
* [ ] interests
    * [ ] client preferences
    * [ ] filter dads
* [ ] user preferred site style
* [ ] account settings
* [ ] admin login
* [ ] update appointments
* [ ] prevent overlapping dad appointments

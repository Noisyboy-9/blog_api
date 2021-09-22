# Blog Api

A TDD implementation of a weblog RESTful API.<br>
This project is a REST api implementation of my other project [laravel](https://github.com/Noisyboy-9/laravel_blog)
with more features.

# features

- Post CRUD: a post can be created, read, updated and deleted.
- Post comment CRUD: a post may have many comments which can be created, read, updated and deleted by the owner.
- Post view: There is a table for saving every post view and its view count and using redis to cache a count of views.
- Category CRUD: a post must have a category which it can be created, read, updated and deleted.
- Post bookmark: a user can bookmark many posts, and can view all bookmarked posts.
- authentication: using laravel sanctum to implement authentication in the project.
- Post Status: a post can be in drafted or published status, and published posts will be fetched in posts feed.
- Post feed: a feed of all published posts.

# technical features

- Using [Pest testing framework](https://pestphp.com/) to implement the api in TDD style using more than 75 feature &
  unit test.
- cleanup models using traits.
- create a reusable system of traits for models to implement new eloquent relationships.
- using custom laravel validation rules for required rules that are not present in laravel/core.
- using form request classes to separate validation logic from controllers logic.
- organize form request classes in App/Http/Requests folder in resource named folders for better codebase organization.
- organize controllers in App/Http/Controllers folder in resource named folders for better codebase organization.
- using integers to represent enum instead of storing an enum in the database.
- using StatusManager classes to separate enum behaviour from other parts of the code.
- using third-party libraries to implement authentication.
- using redis in a well-designed architecture to cache post views count and make it much faster to retrieve post
- views count.

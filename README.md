## About DoDuet
Doduet is one of the main platform of the whole Do Labs environment. The environment is design to be scalable and
large almost with the only limit of imagination because of this many wrappers and helpers will be created around
repetitive functionalities or hard to understand ones.

## Wrappers
The available wrappers currently are:
- [Settings wrapper](#settings-wrapper)
- [Mentions wrapper](#mentions-wrapper)

if you don't know what a wrapper is, wikipedia has the answer for you [here](https://it.wikipedia.org/wiki/Wrapper)


### Wrappers in general
Wrappers are basically short, easy to remember, functions created to easy the development of something difficult or
repetitive to code, in this way a simple and easy method can be used whatever it is needed instead of rewriting a
functionality from scratch.

This means that wrappers reduce code duplication and all the issues related to duplicated code, bugs, vulnerabilities,
spaghetti code and more.

In order to easy the development of wrappers a custom artisan command was created, this command follows the standard
command syntax, it is `make:wrapper` it gives you two possible switch to use:
- `-w / --worker` - needed in case of a worker wrapper should be built
- `-i / --interactive` - needed in case an interactive wrapper should be built

If none of the two types of wrapper is needed a generic wrapper with no predefined methods can be easily created omitting
both the flags.

Consider that wrappers **must** not be fallible, at most a wrapper should throw an exception to be handled by the
developer.

Wrapper should generally be paired with a helper, a laravel global function. In order to easy the development of helpers 
a command was created: `make:helper`.

As all wrappers must be initialized with an `init` static method the helper should simply return the instance of the
initialized wrapper it is paired with.

#### What a worker wrapper is?
A worker wrapper is a wrapper designed to have one public method only, namely `run`, this method will trigger depending
on the data provided and the operation requested a method defined on the wrapper or elsewhere.
This wrapper is designed to offer a great flexibility when there is the need to automate some operation given some 
data.
An example is the [mentions wrapper](#mentions-wrapper)

#### What an interactive wrapper is?
An interactive wrapper heavily differs from a worker one because it will always have three methods:
- `has` - to check if a property exists in the wrapped object or high level instance
- `get` - to retrieve a property from the wrapped object, this method should not be fallible, if the requested property
    is not found a null value should be returned
- `set` - to add or update a property of the wrapped object, this method should always return a boolean indicating the
    result of the operation, `true` for success, `false` otherwise

The interactive wrapper is not flexible as the worker one but gives a clear and easy to use interface for the developers.
An example is the [settings wrapper](#settings-wrapper)

### Settings wrapper
As the settings wrapper is an [interactive wrapper](#what-an-interactive-wrapper-is) it has all the functions of interactive wrappers and nothing more.
It provided a clear and easy to use interface around user's settings, presence checking, setting and updating.

As settings are strongly typed the settings wrapper checks for types error depending on the searched property and 
applies all the needed conversions. The parser works both in reading properties and in writing properties.

In case a user has not set a setting property the default value of the settings is returned.

The available settings types are:
- `int` - the value to be set must strictly be an integer
- `float` - the value to be set must be strictly be a floating point value
- `bool` - the value to be set must be strictly a boolean 
- `string` - the value to be set must be strictly a string
- `json` - this type is design to give complete flexibility to the development of large and complex functionalities.
    Its type can be both a valid json string or an array to be json encoded.

Conversions when retrieving values follow the same logic of when they are set except for the json representation,
json is always decoded to an associative array.

### Mentions wrapper
The mention wrapper is an example of [worker wrapper](#what-a-worker-wrapper-is), it runs the same method whether operation is requested in 
order to avoid confusion.

THe `run` method basically takes an array of data, with the instance of the mentionable object as the first parameter 
and a string with the text (like the description of a track or a comment) from where mentions should be looked up.
If the model is mentionable and the string contains any mention tag formatted as `@<user-alias>` a new mention is 
automatically created and a mention notification is fired, notifying the mentioned user of the new mention.

Mention notifications are broadcasted and stored in the database.

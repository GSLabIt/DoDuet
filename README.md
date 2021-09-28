## About DoDuet
Doduet is one of the main platform of the whole Do Labs environment. The environment is design to be scalable and
large almost with the only limit of imagination because of this many wrappers and helpers will be created around
repetitive functionalities or hard to understand ones.

## Wrappers
The available wrappers currently are:
- [Settings wrapper](#settings-wrapper)
- [Mentions wrapper](#mentions-wrapper)
- [Sodium crypto wrapper](#sodium-crypto-wrapper)
- [Sodium key derivation wrapper](#sodium-key-derivation-wrapper)
- [Sodium encryption wrapper](#sodium-encryption-wrapper)
- [Sodium symmetric encryption wrapper](#sodium-symmetric-encryption-wrapper)
- [Sodium asymmetric encryption wrapper](#sodium-asymmetric-encryption-wrapper)
- [Secure user wrapper](#secure-user-wrapper)

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

#### What a cryptographic wrapper is?
A cryptographic wrapper implements two methods related to cryptography:
- `key` - generates the keys for the algorithm
- `encrypt` - which encrypts a message with an algorithm, a key and a nonce
- `decrypt` - which decrypts a message with an algorithm, and a key extracting the nonce from the body of the message

An example if the [Sodium symmetric encryption wrapper](#sodium-symmetric-encryption-wrapper)

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

### Sodium crypto wrapper
The sodium crypto wrapper is an external wrapper (it wraps other wrappers) with an exception function common to all the 
subsequent wrappers.

As an external wrapper it does not follow any common wrapper patterns instead it provides three methods:
- `derivation` - access the key derivation wrapper
- `encryption` - access the encryption wrapper
- `randomInt` - compute a secure random integer within a range in whatever system it runs on

The `SodiumCryptoWrapper` instance can be accessed with the `sodium()` helper.

### Sodium key derivation wrapper
The sodium key derivation wrapper does not follow any common wrapper patterns and provides all the functions linked to 
cryptographically secure key generation and derivation.

It provides the following methods:
- `generateSalt` - generates a cryptographically secure random string (salt) to be used in encryption as nonce. At least
    you know exactly what you're doing you should never use this function directly instead use the predefined nonce 
    generators
- `generateSymmetricNonce` - generates a cryptographically secure random string to be used in symmetric encryption
- `generateAsymmetricNonce` - generates a cryptographically secure random string to be used in asymmetric encryption
- `generateMasterDerivationKey` - generates a master-pass for all the functionalities that needs keys or seeds. The 
    master-pass is computed based on a provided password (user's password) and a salt that must be saved for subsequently 
    regenerate the same master-pass
- `deriveKeypairSeed` - derive the seed for asymmetric keypair generation
- `packSharedKeypair` - is a utility function that creates a shared keypair with a sender private key and a receiver's 
    public one or with a receiver private key and a sender's one

### Sodium encryption wrapper
The sodium encryption wrapper is an external wrapper.

It does not follow any common wrapper patterns instead it provides two methods:
- `symmetric` - access the symmetric encryption wrapper
- `asymmetric` - access the asymmetric encryption wrapper

### Sodium symmetric encryption wrapper
The sodium symmetric encryption wrapper follows the [cryptographic wrapper](#what-a-cryptographic-wrapper-is) pattern.

It provides all the following methods:
- `key` - generates an XChaCha20 key for authenticated encryption through Poly1305
- `encrypt` - symmetrically encrypts a message of arbitrary length with a composition of the XChaCha20 (encryption) and 
    Poly1305 (authentication) algorithms providing a double layer of security ensuring that a message is encrypted and
    that it cannot be tampered without modifying and recomputing the whole message tag
- `decrypt` - symmetrically decrypts and authenticate a message of arbitrary length with XChaCha20 and Poly1305

### Sodium asymmetric encryption wrapper
The sodium asymmetric encryption wrapper follows the [cryptographic wrapper](#what-a-cryptographic-wrapper-is) pattern.

It provides all the following methods:
- `key` - generates a pair of keys (public and private) for asymmetric cryptography. It uses a seed to deterministically
    compute the keys and avoid the necessity to save the private key (as it can be easily regenerated)
- `encrypt` - encrypt a message of arbitrary length with a composition of XSalsa20 (encryption), Poly1305
    (authentication) and X25519 (key exchange) algorithms providing a triple layer of security ensuring that a message 
    can always be read only by the two parties involved in the communication, that it is encrypted and that it cannot be 
    tampered without modifying and recomputing the whole message
- `decrypt` - decrypts and authenticate a message of arbitrary length with Salsa20 and Poly1305. It uses the key derived 
    from the X25519 key exchange algorithm

### Secure user wrapper
The secure user wrapper follows the [interactive wrapper](#what-an-interactive-wrapper-is) pattern with an extra utility 
method.

It provides all the following methods:
- `has` - check if one of the available properties exists. This method depending on the requested items checks if the 
    item exists in the user's settings or in the current user's session. This method accepts an additional value to the  
    `whitelistedItems` method ones: `all` is a shortcut for rapidly checking the existence of all the properties available
- `get` - retrieve one of the whitelisted items from current user's settings or session depending on the requested 
    property
- `set` - it wraps around a set of virtual properties, `password` and `rotate` both the properties require as their value
    the current user's plain password as they use it to derive cryptographic keys.
    The `password` property triggers the generation or regeneration of the keys for asymmetric encryption while 
    `rotate` property triggers the rotation of all the user's asymmetric keys regenerating a random seed and its 
    associated keys. Key rotation is not reversible and makes all previously encoded text and received messages 
    unreadable.
- `whitelistedItems` - returns a hardcoded list of available items for the `get` and `has` methods

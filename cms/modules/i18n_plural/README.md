Introduction
============

This module has started as a helper class to help achieve accurate language-dependent plural inflections, but has grown
since into almost complete alternative to Kohana 3.1 I18n system (branch for Kohana 3.0 is also available).

Current features are:

 * Support for multiple translation options for any term
 * Support for deep array structures in i18n files
 * Choosing correct translation option when translating plural amount of any term, based on
   [CLDR Language Plural Rules](http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html)
 * Translating and correctly inflecting time spans

Why would you want to use this
==============================

 * You want to be able to use `___('user.register.complete')`, like in old Kohana 2.3.4 days
 * You also want to use just `___('User password')` for short strings
 * You want to inflect the translations depending on various circumstances, such as user's gender and so on.
 * You want to translate things like `I've scanned X directories and found Y files` accurately to any language.
 * You have some legacy code using original Kohana I18n system and you don't want it to break, and you want to reuse some of
   its translations at the same time.
 * You want to have better `Date::fuzzy_span()` output, with actual numbers, again, in any language.
 * You want your validation error messages to be grammatically accurate, too.

The ___() function
==================

The `___()` function (3 underscores, as opposed to 2 underscores being standard Kohana translation function) does the same thing as
its original prototype does, it translates stuff. It has 2 differencies though:

 1. It won't skip translation when the source and destination languages are same. I.e. if your client wants you to change 'sign in'
 in your application to 'log in', you can do so in the corresponding i18n file for default language and don't have to care about all
 the places in your source code, that call `___('sign in')`.
 2. It accepts 2nd optional string or numeric parameter, for providing translation context.

For those, who like shorthands, this is a good news, now you can have whatever keys in your i18n files you want. You can have them either
as strings `('user.register.complete' => 'The user has registered successfully')` or structured, just like Kohana messages, which looks cleaner.

Translation contexts
====================

Many languages use different words or inflections depending on a lot of circumstances, while it isn't much problem in English, we can
find an example there, too: suppose you want to display a string, that looks like this: "His/her name is _name_" and you know the name
of a person and his or her gender. The most trivial would be to do this:

    echo ___($gender == 'f' ? 'His' : 'Her').___('name is :name', array(':name' => $name));

Although you can probably see it's not flexible at all. This message doesn't have to begin with pronoun in other languages.
This is already better:

	echo ___(':their name is :name', array(':name' => $name, ':their' => ___($gender == 'f' ? 'His' : 'Her')));

But what if there is a language, that changes other words as well? That's where the contextual translation comes in handy. Consider
just this:

    echo ___('Their name is :name', $gender);

For that to work, we have defined the translation key `Their name is :name` with 2 contexts - `f` and `m`:

    return array(
        'Their name is :name' => array(
            'f' => 'Her name is :name',
            'm' => 'His name is :name',
        ),
    );

Example
-------

    foreach (array('aimee', 'bob') as $username)
    {
        $person = ORM::factory('profile')->find($username);
        echo ___('Their name is :name', $person->gender, array(':name' => $person->name));
    }

    // Outputs:
    // Her name is Aimee
    // His name is Bob

Example
-------

Some languages distinguish grammatical genders in way more situations, than just pronouns. Also, we can't tell what grammatical
gender a certain word is in different languages, as it may be quite random. Now we see, that we can't specify the required form,
as we did with the given names. In this case, we can think of a context in another way, a context can be just an object we want
it to be related to.

Let's take Russian for an example (i18n/ru.php), although many others will have similar translation structure as well.

    return array(
        'Enabled' => array(
            'user' => 'Включен',
            'role' => 'Включена',
            'other' => 'Включено',
        ),
    );

Somewhere else:

    echo ___('Enabled', 'user');
    // Включен

Note the `other` key, that'll be used for any other context than `user` or `role`.

Plural inflections
==================

If you've ever been bothered by labels like "1 file(s)", search no more, there is a solution for you.

Nice people at CLDR have taken their time to compile plural rules for a large number of languages. This module includes all these
rules and a function, that converts any number into a proper context for that language. The possible contexts are `zero`, `one`,
`two`, `few`, `many` and `other`. Most languages will only have 2-3 of these, and any of them will always have `other` context.

The rules are defined in [these classes](https://github.com/czukowski/I18n_Plural/tree/3.1%2Fmaster/classes/i18n/plural). If you don't
see your language immediately, try looking into one.php, two.php and other generic names, they aggregate a large number of languages,
that share same rules. All the files include the rules in human readable format and a list of languages they apply to.

Example
-------

i18n/en.php:

    return array(
        'You have :count messages' => array(
            'one' => 'You have one message',		// 1 message
            'other' => 'You have :count messages',	// more messages
        ),
    );

i18n/cs.php:

    return array(
        'You have :count messages' => array(
            'one' => 'Máte jednu zprávu',			// 1 message
            'few' => 'Máte :count zprávy',			// 2 - 4 messages
            'other' => 'Máte :count zpráv',			// more messages
        ),
    );

*Note:* before doing something like I did above (I've replaced :count with actual 'one' value for the context `one`), check with the
language rules, whether that context really applies only when the number is 1. There are languages, when this is not the case,
for those languages, you'll have to leave the parameter there.

Example
-------

i18n/en.php:

    return array(
        'hello' => array(
			'myage'=> array(
				'one' => 'Hello world, I\'m :age year old',
				'other' => 'Hello world, I\'m :age years old',
			),
        ),
    );

i18n/ru.php:

    return array(
        'hello.myage' => array(
            'one' => 'Привет мир, мне уже :age год',
            'few' => 'Привет мир, мне уже :age года',
            'many' => 'Привет мир, мне уже :age лет',
            'other' => 'Привет мир, мне уже :age лет',
        ),
    );

In your code:

    echo ___('hello.myage', 1, array(':age' => 1));
    // Hello world, I\'m 1 year old
    echo ___('hello.myage', 2, array(':age' => 2));
    // Hello world, I\'m 2 years old
    echo ___('hello.myage', 10, array(':age' => 10));
    // Hello world, I\'m 10 years old
    
    I18n::lang('ru'); // Switch Kohana to another language
    
    echo ___('hello.myage', 1, array(':age' => 1));
    // Привет мир, мне уже 1 год
    echo ___('hello.myage', 2, array(':age' => 2));
    // Привет мир, мне уже 2 года
    echo ___('hello.myage', 10, array(':age' => 10));
    // Привет мир, мне уже 10 лет

Date and time translating
=========================

This part provides date formatting method, which reflects MooTools [Date.format()](http://mootools.net/docs/more/Native/Date#Date:format)
and better translation. I liked the way MooTools team made date formatting, and especially 'time difference in words' function, since it
gives you a good measure, i.e. "2 weeks ago", instead of Kohana standard "less than a month ago", and it also translates is correctly to
any language. Formatting may come in handy for those, who use [MooTools](http://mootools.net) for their client-side code, so the date/time
format strings and verbose representation can be same for both server and client side.

Currently, i18n files with date and time translations are included for the following languages: Czech, English, Russian. There
is a Polish translation in [Snap's fork](https://github.com/Snap/I18n_Plural), but he's changed syntax a bit
([here's version not yet modified](https://github.com/Snap/I18n_Plural/blob/b2eb01e10e85d7c6db694c71bda214f1e595c228/i18n/pl.php)).

Usage
-----

The `I18n_Date` class extends `Kohana_Date` class, so if you create this:

    class Date extends I18n_Date {}

then you can use it transparently. Only `fuzzy_span()` method is overriden, so that it behaves as MooTools Date.timeDiffInWords()
method. In the following examples, I'll use `Date::method_name()`, but you could as well `I18n_Date::method_name()`, if you
don't want to override `Kohana_Date::fuzzy_span()`.

	$time = time();
	Date::fuzzy_span($time, $time - 10); // -10 seconds
    // less than a minute ago
	Date::fuzzy_span($time, $time - 50); // -50 seconds
    // about a minute ago
	Date::fuzzy_span($time, $time - 100); // 1:40 ago
    // 2 minutes ago
	Date::fuzzy_span($time, $time + 86400); // +24 hours
    // 1 day from now

and so on. The string returned will be translated to the current language.

You can also format dates with various formats using `Date::format()` method. Possible formatting keys are same as with MooTools
[Date.format()](http://mootools.net/docs/more/Native/Date#Date:format) method:

    Date::format($time, '%m/%d/%Y');
    // 10/05/2010
    Date::format($time); // Default is %x %X
    // 10/05/2010 10:53PM
    Date::format($time, 'db'); // using shorthands
    // 2010-10-05 10:53:24
    Date::format($time, 'short');
    // 05 Oct 10:53
    Date::format($time, 'long');
    // October 05, 2010 10:53
    Date::format($time, 'iso8601');
    // 2010-10-05T10:53:24+02:00

If you don't specify format, it will assume `%x %X`, which is a current date and time in the current language prefered format.
It's defined in Kohana translation files, see files from this package for examples ('date' array).

Following format shorthands are currrently supported:

 * db => %Y-%m-%d %H:%M:%S
 * compact => %Y%m%dT%H%M%S
 * iso8601 => %Y-%m-%dT%H:%M:%S%T
 * rfc822 => %a, %d %b %Y %H:%M:%S %z
 * rfc2822 => %a, %d %b %Y %H:%M:%S %z
 * short => %d %b %H:%M
 * long => %B %d, %Y %H:%M
 * header => %g

Note: 'header' format and '%g' key are not in MooTools. I've added them for convenience to use with HTTP headers, that have
dates in them, such as 'Expires' header and so on.

Validation messages translating
===============================

This part aims to provide correct inflection of validation messages. To use it in your project, add this class to your application folder:

    class Validation extends I18n_Validation {}

The overriden function is `Validation::errors()`. It detects the first numeric parameter for a rule and uses it as a context. It is useful
for such fields, as 'decimal', 'min_length', 'max_length' and so on.

The message is now retrieved a little differently: if there's no string found in message files, the function attempts to translate
`{$file}.{$field}.{$error}` path, and failing that, `valid.{$error}`. Lastly, it tries to retrieve default Kohana message for that
kind of error, from 'system/messages/validate.php'. These default messages are translated as `valid.{$error}` in the i18n files
included with this module.

Example
-------

There are 2 ways of defining validation messages directly in i18n files, avoiding messages files:

i18n/en.php

    return array(
        'user' => array(    // "File"-specific
            'password' => array(
                'min_length' => array(
                    'one' => 'New passowrd must be at least one character long',
                    'other' => 'New password must be at least :param1 characters long',
                ),
            ),
        ),
        'valid' => array(   // Default
            'regex' => ':field does not match the required format',
        )
    );

Somewhere else:

    $validation = Validation::factory($_POST)
        ->rule('password', 'min_length', array(6));
    $validation->check();
    $validation->errors('user');
    // array('New password must be at least 6 characters long')

Note, that if matching message exists in message files, the modified `Validation::errors()` function will use it. This is to keep
some kind of backward compatibility.

Installation
============

This module has classes, that extend some Kohana native classes and override some of its functions. To connect and use them,
add these empty classes to your application folder:

To be able to use structured i18n files (like messages files):

    class I18n extends I18n_Core {}

To use custom Date::fuzzy_span():

    class Date extends I18n_Date {}

To use modified Validation::errors() function:

    class Validate extends I18n_Validation {}

*Please note*, that you need to have the Kohana modules loaded by the point, where any of these classes are called. For example,
it is common to find `I18n::lang()` call early in bootstrap.php. You need to move that line somewhere after `Kohana::modules()`
call or you'll get Class not found errors.

API
===

Note: the below reference is not complete yet.

### init.php

#### function ___($string, $context = 0, $values = NULL, $lang = NULL)

Kohana translation/internationalization function with context support. The PHP function [strtr](http://php.net/strtr)
is used for replacing parameters.

    ___(':count user is online', 1000, array(':count' => 1000));
    // 1000 users are online

 * @param string to translate
 * @param mixed string form or numeric count
 * @param array param values to insert
 * @param string target language
 * @return string

### class I18n_Plural

#### public static function get($string, $count = 0)

Returns translation of a string. If no translation exists, the original string will be returned. No parameters are replaced.

    $hello = I18n_Plural::get('Hello, my name is :name and I have :count friend.', 10);
    // 'Hello, my name is :name and I have :count friends.'

 * @param string $string
 * @param mixed $count
 * @return string

#### public static function instance($lang)

Returns class, that handles plural inflection for the given language.

 * @param string $lang
 * @return I18n_Plural_Rules

### class I18n_Date

#### public static function fuzzy_span($from, $to = NULL)

Returns the difference between a time and now in a "fuzzy" way.
Overrides Kohana_Date::fuzzy_span() method.

 * @param integer $from UNIX timestamp
 * @param integer $to UNIX timestamp, current timestamp is used when NULL
 * @return string

#### public static function format($timestamp = NULL, $format = NULL)

Formats date and time.

 * @param mixed timestamp, string with date representation or I18n_Date_Format object; current timestamp if NULL
 * @param string format string or shorthand; '%x %X' if NULL
 * @return string
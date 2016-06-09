capscorrect
===========

Scan a set number of contacts each day, correcting the capitalisation of
individual contacts' first, middle and last names.

The rules for deciding whether a name needs to be corrected are based on
[Xavier's normalise extension](https://civicrm.org/extensions/normalise-data-entered-firstname-last-name).
Any string that is all uppercase, all lowercase, or contains leading/trailing
whitespace, will be trimmed and have the first letter in uppercase, and the
rest of the string in lowercase.

Now compatible with 4.6, thanks to [this PR](https://github.com/futurefirst/capscorrect/pull/6).

**Please note:**
As from some point in summer 2016, Future First is moving its technology
platform away from CiviCRM. This means that our extensions will no longer be
officially maintained on work time, and we will not be upgrading our
installation beyond 4.4. However, I am happy to accept good/useful pull
requests, and hope to maintain some involvement privately.  
-- Dave (@davidknoll on GitHub)

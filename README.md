# alternativenamesearch

This changes the way name searching is done. It was developed to support people
whose names do not have a definite order (no clearly delimited
first/last/given/family names) and may include repeated names. Such
names are common in many African countries.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.0+
* CiviCRM 5.9+
* The [patchwork](https://lab.civicrm.org/extensions/patchwork) extension.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl alternativenamesearch@https://github.com/artfulrobot/alternativenamesearch/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/artfulrobot/alternativenamesearch.git
cv en alternativenamesearch
```

## Usage

Once installed you will be able to search for people by entering their names (or
parts of their names) in any order.

Note that also if someone has a repeated name, eg. <em>Omer Ibrahim Omer Mohammed</em>
you can enter <em>Omer</em> twice (e.g. search <em>Omer Omer</em>) and it will
only find people who have <em>Omer</em> twice in their name.






# agvoy-app

## After cloning

### Import required modules

```
$ composer install
```
```
$ yarn install
```

Be sure to have Yarn and Node.js installed on your computer before

### Compile .js and .scss to public/build directory

```
$ yarn dev
```

### Regenerate DB with Doctrine

```
$ php bin/console doctrine:database:create
```
```
$ php bin/console doctrine:schema:create
```
```
$ php bin/console doctrine:fixtures:load
```
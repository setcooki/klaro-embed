# klaro-embed 

### Klaro! Wordpress embed extension for `<iframe>` and `<embed>` widgets

Do you use Klaro! (https://github.com/kiprotect/klaro) in a Wordpress environment? Good! Did you come across the following 
situation: Your GDPR policy requires you to make certain external providers and services optional (e.g. consume of external media)
Now since we all know that 99.9% of all users accept the privacy choices (default options) presented to them we run sometimes into
an awkward situation. Suppose you have YouTube videos on your webpage which would require the service "YouTube" to be a Klaro app/provider
which, according to your policy needs an active opt-in or lets say the user need to specifically accept the use of that app.
Now if the user clicks the default choices (mandatory or minimum apps needed by the website) the user unknowingly would have not opted-in
to use the "YouTube" service on that website which in turn would omit the use of YouTube videos completely. The user would not even
know that there are YouTube videos embed somewhere on the website because if there is no opt-in the youtube iframe/embed must never
render.

Now wouldn´t it be great that even if the user has not made an active choice/opt-in to consume YouTube videos at least he would be
prompted on every page that there is a video available and that all he needs to do is accept the use of this app (provider/services)
via a iframe/embed overlay that will show you a link to the privacy statement of this provider and an accept button. Once accepted
the choices will be either stored in the Klaro! cookie if the app is mapped in the Klaro! config, or in a separate extension
cookie. In this sense klaro-embed acts as an extension for optional services embedded via iframe/embed code with inline functionality.
Or as a supplement for external services that do not need to appear as a Klaro! app in the Klaro! manager.

### 1) Requirements

None! But this package is supposed to be used in conjunction with Klaro! so you may install it if you want to have a consent manager
with visual interface. But its mandatory because klaro-embed will also work without Klaro! beeing installed.

### 2) Install

```bash
composer require setcooki/klaro-embed
```

Its sufficient to install the package as it will run bootstrapped when composer vendor/autoload.php is loaded. There is no Wordpress
plugin interface or anything else you will see in your wordpress backend

### 3) Configuration

If you are already using Klaro! you can configure klaro-embed by extending the Klaro! config file https://kiprotect.com/docs/klaro/annotated-configuration
If you use the Klaro! Wordpress plugin you must create your own klaro-embed config file and tell the package where to find it. 
But first lets go the configuration and extend it with:

```json
{
  "embed": {
    "provider": [{
      "name": "youtube",
      "app": "youtube",
      "hideApp": true,
      "titleText": "By clicking the following link i agree to YouTube´s <a href='https://policies.google.com/privacy' target='_blank'>Terms of servive</a>",
      "buttonText": "Load video"
    }]
  }
}
```

The following parameters can be used:

| Parameter | Type | Mandatory | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| name | `string` | yes | `null` | Provider name as reference and cookie value |
| app | `string` | no | `null` | Connect this provider to a Klaro! app as found in Klaro! config. See explantion below |
| hideApp | `boolean` | no | `false` | If connected to a Klaro! app hides the provider in consent manager |
| titleText | `string` | no | `null` | The consent title text |
| buttonText | `string` | no | `null` | The consent button text |
| backgroundImage | `string` | no | `null` | Overrides the embed wrapper `background-image: url({backgroundImage})` value |
| embedClass | `string` | no | `null` | Extends the embed wrapper css classes |

All parameters should be self-explanatory. 

The most important parameter is `app`. Unless you connect your provider with a Klaro! app (as set in the Klaro! config) the 
klaro-embed will work independently of Klaro! by storing its own cookie and any change to a Klaro! app consent will have no
effect on this provider.

### 3) Usage

klaro-embed does not know where the Klaro! config resides and if you have access to the config for extending you must tell
klaro-embed where to find the Klaro! config file by setting:

```phpregexp
define('KLARO_EMBED_CONFIG_PATH', 'your/path');
```

You must define the constant before klaro-embed is autoloaded!

If you do not have access to the Klaro! config file (Be it because you use the Klaro! Wordpress Plugin or any other reason)
the package will look for a config file with the name of `klaroConfigEmbed.json` in your themes root directory
or in `config/klaroConfigEmbed.json` in your themes root directory

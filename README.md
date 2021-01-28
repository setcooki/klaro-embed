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

### How does it work (install)


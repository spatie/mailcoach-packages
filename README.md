# The Mailcoach Packages monorepo

This repository contains all the additional feedback & editor packages that you can add to Mailcoach.

## Editors
- [CodeMirror](https://github.com/spatie/laravel-mailcoach-codemirror)
- [Editor.js](https://github.com/spatie/laravel-mailcoach-editor)
- [Monaco](https://github.com/spatie/laravel-mailcoach-monaco)
- [Unlayer](https://github.com/spatie/laravel-mailcoach-unlayer)
- [Markdown](https://github.com/spatie/laravel-mailcoach-markdown-editor)

## Feedback packages
- [Mailgun](https://github.com/spatie/laravel-mailcoach-mailgun-feedback)
- [Postmark](https://github.com/spatie/laravel-mailcoach-postmark-feedback)
- [Sendgrid](https://github.com/spatie/laravel-mailcoach-sendgrid-feedback)
- [SES](https://github.com/spatie/laravel-mailcoach-ses-feedback)
- [Sendinblue](https://github.com/spatie/laravel-mailcoach-sendinblue-feedback)

## Setup packages
- [Mailgun](https://github.com/spatie/laravel-mailcoach-mailgun-setup)
- [Postmark](https://github.com/spatie/laravel-mailcoach-postmark-setup)
- [Sendgrid](https://github.com/spatie/laravel-mailcoach-sendgrid-setup)
- [SES](https://github.com/spatie/laravel-mailcoach-ses-setup)
- [Sendinblue](https://github.com/spatie/laravel-mailcoach-sendinblue-setup)

## Welcome to Mailcoach

[Mailcoach](https://mailcoach.app) is a self-hosted email list manager - in a modern jacket.

It features:
- Subscribers and lists management
- Subscribe, double opt-in and unsubscribe flows
- HTML templates with drop-in variables
- Drafts, previews and test mails
- Send newsletters to an unlimited amount of subscribers
- Statistics and tracking of opens and clicks

In order to be able to install all necessary dependencies you need an mailcoach license you can buy at [mailcoach.app](https://mailcoach.app)

Read our documentation on [how to get started](https://mailcoach.app/docs).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/mailcoach.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/Mailcoach)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

### Monorepo builder

To build the monorepo `composer.json`, run the following command:

```shell
vendor/bin/monorepo-builder merge
```

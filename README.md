# Magento 2 Google reCAPTCHA Free Extension

**Magento 2 Google reCAPTCHA** keeps abusive activities away. By creating a human-friendly shield, bots will be not allowed to access your admin panel and your store, while humans can pass through with ease.

[![Latest Stable Version](https://poser.pugx.org/mageplaza/module-google-recaptcha/v/stable)](https://packagist.org/packages/mageplaza/module-google-recaptcha)
[![Total Downloads](https://poser.pugx.org/mageplaza/module-google-recaptcha/downloads)](https://packagist.org/packages/mageplaza/module-google-recaptcha)


* Add **Invisible CAPTCHA** to all forms on the frontend
* Add **Visible CAPTCHA** to **the backend** *(Featured)*
* Works on **any kinds of forms** *(Featured)*
* Compatible with [Mageplaza extensions](https://www.mageplaza.com/magento-2-extensions/)

![google recaptcha](https://i.imgur.com/bmMVYO3.gif)

## 1. Documentation

- [Installation guide](https://www.mageplaza.com/install-magento-2-extension/)
- [User guide](https://docs.mageplaza.com/google-recaptcha/index.html)
- [Introduction page](http://www.mageplaza.com/magento-2-google-recaptcha/)
- [Contribute on Github](https://github.com/mageplaza/magento-2-google-recaptcha)
- [Get Support](https://github.com/mageplaza/magento-2-google-recaptcha/issues)

## 2. FAQs

**Q: I got error: Mageplaza_Core has been already defined**

A: Read solution [here](https://github.com/mageplaza/module-core/issues/3)

**Q: What types of forms that Google reCAPTCHA can be displayed on?**

A: There are 6 forms which can be selected to display reCAPTCHA on frontend: Login, Forget password, Change password, Product review, Contact us and Registration form. However, admins can insert URL paths and CSS selectors to display reCAPTCHA on any forms they want.

**Q: What types of CAPTCHA that your module is applying?**

A: reCAPTCHA v2 and invisible CAPTCHA are integrated in Magento 2 Google reCAPTCHA.

**Q: Where in a form can I display reCAPTCHA?**

A: You can display reCAPTCHA on the Bottom left, Bottom right and Inline.

**Q: Can I change the language of reCAPTCHA?**

A: Yes, you can choose a language code to display reCAPTCHA on the backend.

## 3. How to install Magento 2 Google reCAPTCHA extension

Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-google-recaptcha
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
## 4. Google reCAPTCHA highlight features

### Invisible reCAPTCHA

**Mageplaza Google reCAPTCHA extension** integrates **invisible reCAPTCHA** which allows humans to pass through the protection with ease, yet it stops bots from abusive activities.

There is no CAPTCHA box within the form’s area, invisible CAPTCHA appears on the right/left bottom of the page which ensures not to distract the user. Invisible CAPTCHA will only popped up if any abusive activities from bots are found, and let humans go through without any interruption.

### Add Google reCAPTCHAs to any forms

There’s no limit to add reCAPTCHAs to any kinds of form on the frontend. Admins can either select available forms or insert URL paths/CSS selectors to display reCAPTCHAs to anywhere they want. There are 6 forms which are available to select with ease:

* Login form
* Registration form
* Forgot password form
* Contact us
* Change password
* Product review

### Backend reCAPTCHAs

**Magento 2 Google reCAPTCHA** also allows **adding CAPTCHA to the backend** in two forms:

* Admin Login
* Admin Forgot Password

![backend captchas](https://i.imgur.com/0ZTFv2i.png)

## 5. More features

### Customize reCaptcha forms

Set no limit to which forms to display **Google reCAPTCHA** by inserting URL post paths and CSS selectors.

### Compatibility
**Google reCaptcha for Magento 2** is highly compatible with other extensions: Mageplaza Blog, Mageplaza Security, Mageplaza Social Login, Mageplaza Gift Card, Mageplaza One Step Checkout.

### Badge positions
**Google reCAPTCHAs** can be displayed right in the form or on the bottom left or on the bottom right of the page

### Multi-language CAPTCHAs
Add a language code to change the language of reCAPTCHAs.

## 6. Full Magento 2 Google ReCaptcha feature list

### For admins
* Turn on/off module
* Change reCAPTCHAs’ language
* Invisible reCAPTCHAs are added to all forms on the frontend
* Visible CAPTCHAs are added to the backend
* CAPTCHA is added to the admin login form
* CAPTCHA is added to the admin’s forgot-password form
* Turn on/off reCAPTCHAs on the frontend
* Be able to add CAPTCHAs to all kinds of forms on the frontend
* Be able to add CAPTCHAs at 3 positions: inline, right bottom and left bottom
* Be able to select themes and sizes for CAPTCHAs
* Compatible with Mageplaza extensions: Blog, Security, Social Login, Gift Card, One Step Checkout. 

### For users

* Invisible CAPTCHAs: users are not disrupted by visible reCAPTCHAs. Humans pass through easily while bots are not allowed to access.

## 7. How to configure Google Recaptcha extension

![How to configure Google Recaptcha extension](https://i.imgur.com/XhPOWBt.png)

### Configuration

#### General configuration

![general configuration](https://i.imgur.com/uZV1gjS.png)

* **Enable**: Choose Yes to turn the module on
* **Language Code** : Select a language to display Google reCAPTCHAs on frontend.

- In the **Invisible** field:

``Note``:  Invisible CAPTCHAs are implemented to the frontend only

* **Google API Key**: Enter the Google API Key 
* **Google API Secret Key**: Enter the Secret Key
* **How to create reCAPTCHA**: See how to add Google reCAPTCHAs [here](https://www.mageplaza.com/blog/how-to-add-google-recaptcha-into-magento-2.html)

- In the **Visible** field:

``Note``: Visible CAPTCHAs are implemented to the backend only

* **Google API Key**: Enter the Google API Key for reCAPTCHAs
* **Google API Secret Key**: Enter the API Secret Key
* **How to create reCAPTCHA**: See how to create Google reCAPTCHA here

#### Backend Configuration

![backend configuration](https://i.imgur.com/5Lq6eSI.png)

* **Enable**: Select Yes to allow Google reCAPTCHA to work on the backend
* **Forms**: Select one or two forms to which are implemented Google reCAPTCHAs:
    * **Forms = Admin Login**: when admins log in to the admin panel, visible reCAPTCHAs will be activated
    * **Forms = Admin Forgot Password**: when admins click on Forgot password to reset it, Google reCAPTCHA is displayed

* **Size**: Select how to display Google reCAPTCHA

![How to configure Magento 2 Google Recaptcha](https://i.imgur.com/fnSovK5.png)

   * Size = Compact: When Compact is selected, Google reCAPTCHA is displayed as below

![size 2](https://i.imgur.com/lPqlIUc.png)

  * Size = Normal: if Normal is selected, Google reCAPTCHA is shown as below:

![size 3](https://i.imgur.com/tcSLJEq.png)

* **Theme**

![theme](https://i.imgur.com/nJZr4Ha.png)

* **Theme = Light**: The light color is applied for reCAPTCHA
* **Theme = Dark**: The dark color is applied for reCAPTCHA

#### Frontend Configuration

![frontend configuration](https://i.imgur.com/9Os1kit.png)

* **Enable**: Select Yes to turn on invisible reCAPTCHA on the frontend
* **Forms**: Admins can select one or some forms to implement reCAPTCHAs to
    * **Forms = Login**: when visitors log in to the page
    * **Forms = Create User**: when visitors fill in the registration form
    * **Forms = Forgot Password**: when visitors forget their passwords and fill in the Forget-password form
    * **Forms = Contact Us**: when visitors fill in the Contact Us form
    * **Forms = Change Password**: when visitors fill a form to change password
    * **Forms = Product Review**: when visitors visit a product page to leave a product review

* **Badge Position**

![badge position](https://i.imgur.com/NNUWZLj.png)

   * **Badge Position = Inline**: display reCAPTCHA in selected forms
   * **Badge Position = Bottom Right**: display reCAPTCHA on the bottom right of the page
   * **Badge Position = Bottom Left**: display reCAPTCHA the bottom left of the page

* **Theme**

![theme 2](https://i.imgur.com/bLmnEkB.png)

   * **Theme = Light**: The light color is applied for reCAPTCHA
   * **Theme = Dark**: The dark color is applied for reCAPTCHA

* **Custom Form Position**: display reCAPTCHA to any kinds of forms

![custom form position](https://i.imgur.com/eWjgErF.png)


* **Form Post Paths**

    * Enter the path (url) where will process your form information

* For example: newsletter/subscriber/new/, display reCAPTCHA in the email address field on the website.

* **Below are some detailed steps**:

- **Step 1**: Log in to your website
- **Step 2**: Choose Form that you want to display reCAPTCHA and open the Test window
- **Step 3**: After that, choose Element tab, then choose the form that contains the field you want to add reCAPTCHA

![example](https://i.imgur.com/IqaJHxq.png)

- **Step 4**: In the Subscriber Form in the Action field, copy the URL path. (In this example, the URL is "newsletter/subscriber/new/")
- **Step 5**: Paste the URL into the Form Post Paths field
    * The paths are separated by downstream.
    * If you this field is empty and you fill in the information in the CSS Selectors field, reCAPTCHA is still displayed on the form that you fill in the CSS Selectors field, but it still doesn’t work on that form.

![example 2](https://i.imgur.com/SiWJCdB.png)

* **CSS Selectors**
    * You need to take CSS Selector of the form. At our example, we will take ID of the form.

* For example: #newsletter-validate-detail, display reCAPTCHA in the Subscriber

![example 3](https://i.imgur.com/dfrLQnR.png)

   * The IDs should be separated by downstream.
   * If you leave this field empty while filling in the information in Form Post Paths field, reCAPTCHA won't be displayed on the form you want.

``Note``:

* To display Google reCAPTCHAs in a custom form, you have to fill in two fields: Form Post Paths and CSS Selectors.
* If you are not able to access to the backend while reCAPTCHA is enabled, you can use the following command to disable the module via composer. After that, you can access the admin panel again.

`php bin/magento module:disable Mageplaza_Recaptcha`

Or, use this command below to disable the configuration:

`php bin/magento mageplaza:core:disable Mageplaza_Recaptcha`

#### How Google reCAPTCHA is displayed on the Magento 2 frontend

![the display on the frontend](https://i.imgur.com/rMoqd2v.png)

![display on frontend 1](https://i.imgur.com/9i3SkDQ.png)

#### How Google reCAPTCHA is displayed in the Magento 2 backend

![the display in the backend](https://i.imgur.com/91H3ERd.png)


**People also search:**
- mageplaza google recaptcha
- magento 2 recaptcha
- google recaptcha magento 2
- magento 2 invisible recaptcha
- google captcha magento 2
- magento 2 recaptcha extension
- recaptcha magento 2
- magento 2 google captcha
- magento 2 captcha extension free
- google invisible recaptcha magento 2
- invisible recaptcha magento 2
- magento 2 recaptcha v3
- magento 2 google invisible recaptcha
- recaptcha v3 magento 2


**Other free extension on Github**
- [Magento 2 SEO](https://github.com/mageplaza/magento-2-seo)
- [Magento 2 Google Maps](https://github.com/mageplaza/magento-2-google-maps)
- [Magento 2 Blog](https://github.com/mageplaza/magento-2-blog)
- [Magento 2.x GDPR compliance](https://github.com/mageplaza/magento-2-gdpr)
- [Magento 2 social login](https://github.com/mageplaza/magento-2-social-login)
- [Magento 2 Same Order Number](https://github.com/mageplaza/magento-2-same-order-number)
- [Magento 2 Layered Navigation](https://github.com/mageplaza/magento-2-ajax-layered-navigation)
- [Magento 2 security](https://github.com/mageplaza/magento-2-security)












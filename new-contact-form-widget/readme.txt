=== Contact Form Widget - Responsive Contact Form, Query Form & Form Builder ===
Contributors: awordpresslife, razipathhan, hanif0991, muhammadshahid, fkfaisalkhan007, sharikkhan007, zishlife, FARAZFRANK
Donate link: https://paypal.me/awplife
Tags: contact form, query form, form builder, contact form widget, email form
Requires at least: 4.0
Tested up to: 7.0
Stable tag: 1.5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create responsive contact forms with stored query table management. Easy contact form shortcode and widget for inquiries and lead generation.

== Description ==

Contact Form Widget is a lightweight, responsive contact form plugin for WordPress designed to collect user inquiries, leads, and customer feedback effortlessly. Whether you need a simple sidebar contact form widget or a full-width contact form shortcode inside your posts and pages, this contact form plugin provides an intuitive setup with complete administrative control.

Unlike basic contact form tools that rely solely on email notifications, every contact form submission sent through this plugin is saved directly to your WordPress database. You can manage, review, and organize all incoming messages inside a dedicated admin query dashboard, ensuring you never miss a visitor inquiry or client lead.

**View Pro Demo:** **[Contact Form Premium Demo](https://awplife.com/demo/contact-form-premium/)**  
**More About Pro:** **[Contact Form Premium Details](https://awplife.com/wordpress-plugins/contact-form-wordpress-plugin/)**  
**Try Pro Version:** **[Test Premium Plugin](https://awplife.com/demo/contact-form-premium/how-to-test-premium-plugin/)**

= Why Choose This Contact Form Plugin? =

Building a functional contact form should be straightforward. This plugin combines the simplicity of a drag-and-drop contact form widget with the flexibility of a shortcode-based form builder. It handles real-time AJAX form validation, protects submissions with security nonces, and offers custom design controls so your contact form matches your site branding seamlessly.

= Key Features of Contact Form Widget =

* **Stored Contact Queries** – Automatically saves every contact form message in your database table for easy tracking and record keeping.
* **Dual Display Options** – Embed your contact form using the shortcode `[CFW]` or place it in any sidebar or footer widget area.
* **Asynchronous AJAX Submission** – Visitors can submit the contact form instantly without page reloads, complete with loading spinners and inline confirmation notices.
* **Pre-Built Layout Templates** – Select between Template 1 (classic centered layout) and Template 2 (modern two-column grid layout for Name and Email).
* **Full Design Customization** – Adjust form background color, title color, max-width slider (10% to 100%), and container alignment (left, center, right).
* **Custom Field Placeholders** – Customize text labels and placeholder prompts for Name, Email, Subject, and Message fields.
* **Custom Error & Success Messages** – Tailor validation error alerts for blank inputs or invalid email addresses, as well as submission response messages.
* **Custom CSS Editor** – Add your own custom CSS styling directly from the settings panel to tweak form appearances without editing theme files.
* **CSV Export Tool** – Export all stored contact form queries into a structured CSV file for offline reporting and customer relationship management.
* **Admin Query Dashboard** – Sort, paginate (5 to 250 records per page), view details in a popup modal, and delete single or bulk form entries.

= Pro Features =

Upgrade to Contact Form Premium for enhanced control and advanced form building capabilities:

* **Google reCAPTCHA Integration** – Block spam submissions and automated bots.
* **SMTP Email Support** – Deliver notifications reliably via Gmail or custom SMTP servers.
* **Auto Email Responder** – Send automatic reply emails to visitors after contact form submission.
* **Custom Email Templates** – Personalize administrative and user notification emails.
* **Submission Analytics** – View daily form submission trends and statistical reports.
* **Priority Premium Support** – Access dedicated support from our technical team.

== Installation ==

1. Log in to your WordPress admin dashboard.
2. Go to **Plugins > Add New**.
3. Search for **Contact Form Widget**.
4. Click **Install Now** and then activate the plugin.
5. To place the form in a widget area, go to **Appearance > Widgets** and drag **Contact Form Widget** to your desired sidebar.
6. To embed the form on any page, post, or page builder section, paste the shortcode `[CFW]`.
7. Go to **Contact Form Queries > Settings** to customize form templates, colors, text labels, and response messages.

== Frequently Asked Questions ==

= How do I display the contact form on a page or post? =
Simply paste the shortcode `[CFW]` inside any page, post, or block editor section. You can also use page builders like Elementor, Beaver Builder, or Divi by adding the shortcode block.

= How do I add the contact form to my website sidebar or footer? =
Go to **Appearance > Widgets** in your WordPress dashboard, find the **Contact Form Widget**, and drag it into your active sidebar or footer widget area.

= Are contact form submissions saved in WordPress? =
Yes. All submissions sent through the contact form are stored in your WordPress database under the `{wp_prefix}awp_contact_form` table. You can view, search, and manage all entries from **Contact Form Queries** in your admin panel.

= Can I export contact form entries to Excel or CSV? =
Yes. On the **Contact Form Queries > All Users Queries** page, click the **Download Query List** button to download a complete CSV report containing submitter names, email addresses, subjects, messages, and timestamps.

= Can I customize the colors and width of the contact form? =
Yes. Under **Contact Form Queries > Settings**, you can choose your title color, form background color, container width percentage, and overall form alignment (left, center, or right).

= Is the contact form mobile responsive? =
Yes. Both form templates adapt automatically to screen sizes on mobile phones, tablets, laptops, and desktop computers.

= Does the contact form use AJAX for submission? =
Yes. Submissions are processed asynchronously in the background without refreshing the web page.

= Can I change the field placeholders and error messages? =
Yes. You can edit the placeholder text for Name, Email, Subject, and Message, as well as all validation error messages and confirmation notices directly from the plugin settings panel.

== Screenshots ==

1. Contact form displayed on site frontend using shortcode [CFW]
2. Contact form layout template selection and design options
3. Form header and color customization settings panel
4. Form label and custom placeholder text settings
5. Custom validation error message configuration
6. All user contact queries database table in WordPress admin
7. Viewing detailed user query in administrative popup modal

== Changelog ==

= 1.5.3 =
* Security: Neutralized CSV formula injection risks during export and added UTF-8 BOM encoding.
* Security: Implemented hidden anti-spam honeypot verification for form submissions.
* Security: Secured dashboard cache refresh with strict capability checks and nonce verification.
* Performance: Optimized queries dashboard pagination using COUNT(*) for minimal memory consumption.
* Performance: Replaced iterative database deletions with a single bulk query.
* Performance: Added index on query timestamps and upgraded table ID schema to bigint unsigned.
* Feature: Added automatic admin email notifications with Reply-To headers upon form submission.
* Feature: Added dedicated AJAX handler and user feedback banner for settings configuration.
* Fix: Isolated form styling strictly under `.cfw-container` to prevent bleed into theme styles and buttons.
* Fix: Refactored AJAX script to support multiple contact forms on the same page.
* Fix: Corrected query submission timestamp formatting to standard 24-hour MySQL datetime.
* Fix: Fixed background color application on sidebar widget displays.
* Compatibility: Added transient caching to theme recommendations dashboard.
* Compatibility: Standardized permissions check to manage_options.
* Compatibility: Full PHP 8.2+ compatibility with safe null coalescing defaults across all options.

= 1.5.2 =
* Fixed View Query modal popup rendering in admin dashboard.
* Hardened security with enhanced nonce verification and capability checks.
* Optimized CSV query list download handling to prevent header errors.
* Refactored script enqueuing for improved compatibility.

= 1.5.1 =
* Tested and confirmed compatibility with WordPress 6.9.

= 1.5.0 =
* Resolved CSV download button issue and updated administrative scripts.

= 1.4.9 =
* Maintenance update and code optimizations.

== Upgrade Notice ==

= 1.5.3 =
Major release featuring security hardening (anti-spam honeypot, CSV sanitization), admin email notifications, scoped CSS isolation, query database performance optimizations, and full PHP 8.x compatibility.

= 1.5.2 =
Recommended update for enhanced administrative security, modal script fixes, and seamless CSV query exports.

# WP Year Calendar

A WordPress page template that displays an annual calendar of blog posts.

The calendar shows all twelve months of a selected year. Dates with published posts link to the corresponding daily archive, and month names link to monthly archives when that month contains at least one published post.

## Features

- Annual calendar covering the blog's publication history.
- Year navigation from the first published post year through the current year.
- All twelve months are displayed, including months without published posts.
- Days with published posts link to their daily archives.
- Month names link to their monthly archives when applicable.
- The `title` attribute on linked dates indicates the number of posts published that day.
- "Today in the past years" marker for the corresponding date in earlier years.
- Responsive layout: a 3-column by 4-row month grid on screens at least 1024 pixels wide, and a vertical month layout on narrower screens.
- Uses native WordPress functions and the WordPress database.

## Requirements

- WordPress.
- A classic PHP theme that supports page templates.
- PHP functions required by WordPress, including `cal_days_in_month()`.

## Installation

1. Back up your site and, preferably, use a child theme.
2. Copy `wpyearcalendar.php` into the active theme (or child theme) directory.
3. Open `wp-year-calendar.css`.
4. Copy its contents into the active theme's `style.css`. Do not overwrite the theme's existing `style.css`; append the calendar rules to it.
5. In WordPress, create or edit a page.
6. Select **Year Calendar of the Blog** in the page template settings.
7. Publish or update the page.

The template uses the theme's standard `get_header()` and `get_footer()` functions. It does not include a theme-specific sidebar, advertising calls, or custom header/footer variants.

## Customisation

- The weekday initials are defined in the `$weekdays` array in `wpyearcalendar.php`. Change them manually to match your language.
- The visible words `post` and `posts` in the date `title` attribute are currently in English and can be edited in the PHP file.
- Calendar styles can be adjusted in the theme stylesheet.

## Notes

- The template is designed for standard published WordPress posts only. It does not include pages or custom post types.
- The year selector uses the `yr` URL parameter.
- The calendar cache uses WordPress transients with a 12-hour expiration.
- The template assumes that the site has at least one published post.
- The final appearance depends on the active theme.

## License

This project is licensed under the GNU General Public License, version 2 or (at your option) any later version. See [LICENSE](LICENSE).

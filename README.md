# AMS Data Charts WordPress Plugin

**Features:**

- Simple shortcode-based integration (e.g., `[ams_data_chart]`)
- Customizable attributes to tailor the displayed feed
- Reliable and real-time data updates from the AMS API

**Use Case:** This plugin is perfect for water plant operators, municipalities, and organizations that need to share water quality data with the public or internal stakeholders in a clear and accessible format.

## Attribute Usage

**Type (required):**

- Chromium: `[ams_data_chart type="mg"]`
- Trihalomethanes: `[ams_data_chart type="tthm"]`

**City (required):**

- Benicia: `[ams_data_chart type="tthm" city="Benicia"]`
- Sunnyvale: `[ams_data_chart type="tthm" city="Sunnyvale"]`
- San Bernardino County: `[ams_data_chart type="mg" city="San Bernardino County"]`

_**Note:** The `city="San Bernardino County"` attribute can only be used with the `type="mg"` attribute. San Bernardino County **does not** exist in the Trihalomethanes data feed._

## Changelog

- **1.0.1** — March 5, 2025
  - Added additional "type" attribute.
  - _**Please read the updated [Attribute Usage](#attribute-usage) section.**_
  - Updated WordPress plugin description.
- **1.0.0** — March 3, 2025
  - Initial public release.

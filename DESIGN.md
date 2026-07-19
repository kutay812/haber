---
name: Informer Pulse
colors:
  surface: '#fcf9f8'
  surface-dim: '#dcd9d9'
  surface-bright: '#fcf9f8'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f6f3f2'
  surface-container: '#f0eded'
  surface-container-high: '#eae7e7'
  surface-container-highest: '#e5e2e1'
  on-surface: '#1c1b1b'
  on-surface-variant: '#43474f'
  inverse-surface: '#313030'
  inverse-on-surface: '#f3f0ef'
  outline: '#737780'
  outline-variant: '#c3c6d1'
  surface-tint: '#3a5f94'
  primary: '#001e40'
  on-primary: '#ffffff'
  primary-container: '#003366'
  on-primary-container: '#799dd6'
  inverse-primary: '#a7c8ff'
  secondary: '#bc000c'
  on-secondary: '#ffffff'
  secondary-container: '#e80f16'
  on-secondary-container: '#fffbff'
  tertiary: '#381300'
  on-tertiary: '#ffffff'
  tertiary-container: '#592300'
  on-tertiary-container: '#d8885c'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d5e3ff'
  primary-fixed-dim: '#a7c8ff'
  on-primary-fixed: '#001b3c'
  on-primary-fixed-variant: '#1f477b'
  secondary-fixed: '#ffdad5'
  secondary-fixed-dim: '#ffb4aa'
  on-secondary-fixed: '#410001'
  on-secondary-fixed-variant: '#930007'
  tertiary-fixed: '#ffdbca'
  tertiary-fixed-dim: '#ffb690'
  on-tertiary-fixed: '#341100'
  on-tertiary-fixed-variant: '#723610'
  background: '#fcf9f8'
  on-background: '#1c1b1b'
  surface-variant: '#e5e2e1'
typography:
  display-hero:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '800'
    lineHeight: 56px
    letterSpacing: -0.02em
  display-hero-mobile:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '800'
    lineHeight: 38px
    letterSpacing: -0.01em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
  headline-sm:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 24px
  body-lg:
    fontFamily: Source Serif 4
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 30px
  body-md:
    fontFamily: Source Serif 4
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 26px
  label-caps:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '700'
    lineHeight: 16px
    letterSpacing: 0.05em
  meta-data:
    fontFamily: Inter
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 18px
spacing:
  container-max: 1280px
  gutter: 20px
  margin-mobile: 16px
  section-gap: 48px
  component-padding: 12px
---

## Brand & Style
The design system is engineered for a high-velocity news environment that prioritizes authority, immediacy, and density. It targets an informed audience seeking a structured, professional, and trustworthy digital newspaper experience.

The style is **Corporate Modern with a focus on Information Density**. It avoids unnecessary ornamentation, favoring a strict grid and clear visual hierarchy to organize a high volume of content without overwhelming the reader. The aesthetic is "Newspaper-Digital," blending the traditional gravitas of journalism with the speed and flexibility of modern web standards. White space is used functionally—to separate distinct news categories—rather than purely decoratively.

## Colors
The palette is built on the pillars of traditional news media. 

- **Primary (Deep Navy):** Used for the global navigation, primary buttons, and authoritative UI elements. It establishes the "anchor" of the visual brand.
- **Secondary (Vibrant Red):** Specifically reserved for high-urgency signals: breaking news labels, live stream indicators, and active category states.
- **Neutral:** A range of grays used for secondary text and borders. The core neutral is a deep ink-black to ensure maximum legibility against the white background.
- **Background:** A crisp white (#FFFFFF) is the primary canvas, ensuring a "clean paper" feel that enhances text readability.

## Typography
The typographic strategy balances **impact** and **legibility**. 

**Inter** is utilized for headlines, labels, and navigation. Its geometric clarity provides a modern, professional look that remains legible even at small sizes in dense layouts. Bold and Extra Bold weights are used for the news grid to create a clear "scan-line" for readers.

**Source Serif 4** is the primary choice for article body text. This serif face is optimized for long-form reading, providing a literary quality that reduces eye strain and reinforces the authoritative nature of the reporting.

Headline sizes scale down aggressively for mobile to ensure that headlines remain visible "above the fold" on smaller screens.

## Layout & Spacing
The layout follows a **12-column Fixed Grid** for desktop (1280px max-width) to maintain a classic editorial structure. 

- **Hero Grid:** A "Stage" layout where the lead story spans 8 columns, with a vertical stack of 4 secondary stories in the remaining 4 columns.
- **Sidebar:** Standardized at 4 columns (approx. 300-340px) for trending lists and advertising units.
- **Density:** Spacing is tighter than typical SaaS products (8px/12px increments) to allow more headlines to be visible simultaneously.
- **Mobile:** Reflows to a single column. All horizontal "Hero" grids transform into vertical lists or "swipeable" carousels for category sections.

## Elevation & Depth
This design system uses **Tonal Layers and Low-Contrast Outlines** rather than heavy shadows. 

The depth hierarchy is flat to maintain a "printed" feel. 
- **Base Level:** White background.
- **Level 1 (Cards/Sections):** Defined by thin 1px borders in a light gray (#E5E5E5). 
- **Hover States:** Subtle "lift" effect using a very soft, diffused shadow (0px 4px 12px rgba(0,0,0,0.05)) to indicate interactivity without breaking the grid's structural integrity.
- **Breaking News Ticker:** Uses a slight primary color tint or a solid red background to sit "above" the content stream.

## Shapes
The shape language is **Sharp (0px)**. 

To convey professionalism and a serious journalistic tone, the design system avoids rounded corners. Square edges on images, buttons, and input fields provide a structured, architectural feel. This "sharp" aesthetic mirrors the precision of traditional broadsheet newspapers.

## Components
- **Breaking News Ticker:** A full-width bar below the header. The label "SON DAKİKA" (or "BREAKING") is pinned in Vibrant Red with white text, while the news scrolls in a high-contrast black bar.
- **News Cards:** Image-heavy with a sharp 1px border. Headlines are placed below the image. For "Lead Stories," the headline may overlay the bottom of the image with a subtle dark gradient for legibility.
- **Category Chips:** Small, rectangular labels using Primary Navy. Used to tag stories (e.g., "ECONOMY", "SPORTS").
- **Buttons:** Solid Primary Navy for main actions; ghost buttons with 1px black borders for secondary actions. Always rectangular.
- **Trending Sidebar:** A numbered list (1-10) using large, low-opacity gray numerals for the rank, followed by a bold Inter headline.
- **The "Pulse" Indicator:** A small, animated red dot used next to "LIVE" or "WATCH" labels to indicate real-time video coverage.

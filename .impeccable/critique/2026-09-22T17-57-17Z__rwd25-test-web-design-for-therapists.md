---
target: "http://rwd25.test/web-design-for-therapists/"
total_score: 14
max_score: 36
na_heuristics: 7
p0_count: 1
p1_count: 4
target_identity: "url:http://rwd25.test/web-design-for-therapists"
timestamp: 2026-09-22T17-57-17Z
slug: rwd25-test-web-design-for-therapists
---
# Design Health Score

| # | Heuristic | Score | Key issue |
|---|---|---:|---|
| 1 | Visibility of system status | 1/4 | The estimator changes visually but is not announced to assistive technology; the failed booking embed provides no useful recovery path. |
| 2 | Match with the real world | 2/4 | The page has empathetic language, but generic growth, Google, automation and template language does not reflect how therapists describe fit, referrals and private practice. |
| 3 | User control and freedom | 1/4 | CTA labels do not reliably predict destinations, one anchor is missing and the booking destination is invalid. |
| 4 | Consistency and standards | 2/4 | The visual system is cohesive, but CTA behaviour and the relationship between the custom build and £30/month care offer are inconsistent. |
| 5 | Error prevention | 1/4 | The calculator and “legal pages included” wording invite assumptions about scope; the primary booking route was able to ship with an invalid URL. |
| 6 | Recognition rather than recall | 3/4 | Headings and grouped sections scan well, but ownership, content responsibilities, privacy and ongoing costs must be inferred. |
| 7 | Flexibility and efficiency | n/a | Not useful for this persuasive landing-page surface. |
| 8 | Aesthetic and minimalist design | 2/4 | Attractive and spacious, but long, repetitive and diluted by irrelevant or unfinished proof. |
| 9 | Error recovery | 0/4 | Calendly reports an invalid URL and the page offers no native alternative contact route at that decision point. |
| 10 | Help and documentation | 2/4 | FAQs exist but omit several of the audience's highest-stakes questions. |
| **Total** |  | **14/36** | **Poor: good visual foundation, but credibility and task-completion problems need attention.** |

# Design Specificity Verdict

The page has an authored look but a generic argument. Its pale blue and cream palette, serif display type, watercolour imagery and founder-led presentation feel calm and human. The copy, however, could largely be reused for a coach, wellness practitioner or ordinary service business.

The page does not yet demonstrate the sector understanding established in the audience research: therapeutic fit, exact credentials, session format, fees, referral and directory journeys, sensitive enquiries, ownership, preserving useful existing content and making the first contact feel manageable.

The browser pass confirmed several concrete implementation problems. The page has three H1 elements because the header and footer logos are marked up as headings; the visually dominant hero proposition is plain text. The estimator lacks an accessible name and live status updates. The mobile menu remains exposed to keyboard and screen-reader users while off-canvas. FAQ triggers are mouse-only divs. Several low-contrast text styles fall below WCAG AA. No detector overlay was available because the browser evaluation surface was read-only.

# Overall Impression

The section set is fundamentally right. The page already contains the pieces a therapist needs: recognition, price context, human reassurance, process, work, answers and a next step. The problem is sequencing and specificity, not missing volume.

The biggest opportunity is to change the page from “Mike can build you an affordable website” to “Mike understands how a private-practice website helps the right person decide whether to contact you.”

Recommended order:

1. Hero
2. Intro and problem recognition
3. What I can do, reframed around what a therapy website needs
4. See what is possible, using relevant therapist work
5. Testimonials paired with the relevant work
6. About Mike
7. How it works
8. Indicative price calculator
9. Ownership, handover and optional Website Momentum, either as a short bridge or within process/FAQs
10. FAQs
11. Contact

# What Is Working

1. The visual tone is calm, friendly and appropriate for a trust-led audience. It avoids the loud, corporate style that would work against the offer.

2. The price estimator addresses a genuine objection. Therapists are uncertain about scope and cost, so an indicative range can reduce anxiety. Its current position and vocabulary are the problems, not the concept itself.

3. The founder and process sections reduce risk. A visible human, a manageable sequence and plain-language reassurance support the audience's desire to feel listened to and guided.

# Priority Issues

## [P0] The primary contact journey is broken

**Why it matters:** The embedded Calendly page reports that its URL is invalid. Hero booking links point to the same destination, while the estimator CTA points to a nonexistent anchor. A visitor who decides to act cannot reliably complete the page's primary task.

**Fix:** Correct the Calendly URL, make the estimator CTA point to the actual contact section and provide a simple native alternative such as an email link or short enquiry form. Test every CTA on desktop and mobile.

**Suggested command:** `$impeccable harden`

## [P1] The proof weakens the therapist-specialist position

**Why it matters:** The first testimonial is from a coach. The work section leads with an astrologer, includes an estate-agent integration and contains visible placeholder text. A therapist may conclude that this is a generic landing page with their title inserted.

**Fix:** Lead with Paul, Emiliana and any approved psychology work. Give each example a professional title, practice context, starting problem and two or three relevant decisions. Pair testimonials with their corresponding project. Move coaching-only proof to the future coaches page.

**Suggested command:** `$impeccable clarify`

## [P1] The calculator arrives before the page has earned the price conversation

**Why it matters:** The visitor is asked to configure templates and features before the offer, relevant proof or process is clear. The £750 anchor makes a bespoke WordPress service feel like a commodity and conflicts with the broader website-as-an-asset position.

**Fix:** Move the calculator after proof and process. Ask in buyer language: new site or redesign, solo or group practice, number of distinct services, content support, migration, booking and resources. Present a non-binding range and explain what changes it.

**Suggested command:** `$impeccable layout`

## [P1] The copy misses what makes a therapy website different

**Why it matters:** “Expand your reach,” “attract more clients,” lead magnets and generic Google urgency do not answer the buyer's main question: whether RWD understands therapists and private practice.

**Fix:** Lead with helping the right clients find, understand and contact the practice. Name counsellors, psychotherapists and psychologists near the top. Reframe “What I Can Do” around fit, trust, practical clarity, credentials, privacy-conscious contact and a gentle next step.

**Suggested command:** `$impeccable clarify`

## [P1] The custom build and ongoing offer are muddled

**Why it matters:** “Hosting and updates for £30/month” can sound compulsory and reduces the ongoing relationship to cheap maintenance. This conflicts with the stronger Website Momentum proposition and the audience's fear of lock-in.

**Fix:** State that the client receives an editable WordPress site and can manage it after handover. Present Website Momentum as an optional next chapter covering maintenance, visibility, content, performance and ongoing improvement. Clarify ownership, transfer and recurring costs.

**Suggested command:** `$impeccable clarify`

## [P2] Accessibility and copy defects erode trust

**Why it matters:** The mouse-only FAQs, inaccessible hamburger, hidden-but-focusable mobile menu, low-contrast light text, malformed heading structure and visible typos make the page feel less careful than the service promises.

**Fix:** Use semantic buttons with ARIA state for the menu and accordions, hide closed navigation properly, announce estimator updates, correct the H1 structure, improve contrast and proofread the page. Hide inactive testimonial slides from assistive technology.

**Suggested command:** `$impeccable audit`

# Persona Red Flags

## Jordan — first-time private-practice owner

- “Templates,” “lead magnet,” “basic SEO” and “automation” assume marketing knowledge.
- It is unclear who supplies copy and legal wording, what the client owns or what happens after launch.
- “See Pricing” unexpectedly opens a booking service rather than the visible estimator.
- Therapists, counsellors and coaches are blended together, weakening confidence in the specialism.

## Riley — careful established practitioner

- “Built to be found on Google” is stronger than the honest no-guarantee SEO position.
- The estimator looks precise without covering migration, content or scope boundaries.
- “Legal pages included” is ambiguous about whether RWD supplies legal text.
- Unrelated projects, placeholder copy and typographical errors create a gap between the promise of care and the evidence.
- Ownership, cancellation and transfer are not answered clearly.

## Casey — distracted mobile visitor

- The page is nearly 15,000 pixels tall on mobile.
- The calculator asks for multiple scope decisions too early.
- Repeated CTA labels lead to different or broken destinations.
- The failed 800-pixel Calendly embed creates a large dead area and cookie banner near the end of the journey.

# Minor Observations

- The visible hero statement should be the single page H1; logo text should not be marked up as H1.
- “See Pricing” should scroll to the estimator.
- “FAQs” does not need an apostrophe.
- Correct “ensure every things on track,” “a care packages” and “15–30 minuet call.”
- Replace the long standalone testimonial with shorter, project-linked proof.
- Add FAQs covering copy support, SEO limitations, ownership and transfer, sensitive enquiries, recurring costs and self-management versus Website Momentum.
- “Psychology Today” is less UK-specific than Counselling Directory, BACP, UKCP, NCPS or professional referrals.
- The service body text, eyebrow labels and hero kicker need stronger contrast and/or weight.

# Questions to Consider

- Is the page primarily selling a configurable £750 website, a bespoke strategic project or the start of a Website Momentum relationship?
- What three details should convince a referred therapist within 20 seconds that RWD understands private practice?
- What is the honest boundary around copywriting, legal wording, privacy responsibilities, SEO outcomes and ownership?
- Would every project and testimonial still earn its place if it had to increase confidence specifically among counsellors, psychotherapists or psychologists?

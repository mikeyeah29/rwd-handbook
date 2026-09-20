# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

delegated: plain PHP with Composer support for Markdown rendering; no framework is required for this documentation viewer.

## Users

The primary user is the business owner maintaining Rockett Web Design's internal handbook and wanting to browse its Markdown source in a clearer, more usable way.

## Product Purpose

Turn this repository of business, brand, service, marketing, sales and playbook notes into a small browser-based handbook. Success means the owner can find a topic quickly, read it comfortably, and return to the relevant folder without manually opening Markdown files.

## Positioning

The site reads the repository directly, so the handbook stays a single source of truth while gaining a navigable, styled reading experience.

## Operating Context

The site runs locally or on a PHP host from the repository root. Top-level numbered folders define the main sections; Markdown files inside them become the handbook pages.

## Capabilities and Constraints

- The home page lists folders and their Markdown files.
- Individual pages render Markdown as HTML and include previous, next and home navigation.
- The viewer must handle empty Markdown files gracefully.
- File paths must be constrained to Markdown files inside the repository.
- Existing Markdown content remains the source material; the viewer must not invent business claims.

## Brand Commitments

The source material identifies the business as Rockett Web Design (RWD). The interface should feel clear, calm, specific and useful rather than like a generic marketing site.

## Evidence on Hand

The repository's Markdown files are the available source material. There are no supplied logos, illustrations or image assets, so the interface should not fabricate them.

## Product Principles

- Make the structure visible before asking the reader to search.
- Let the writing remain the visual centre of the experience.
- Keep navigation close to the document without crowding it.
- Prefer a small, maintainable implementation over a framework-heavy shell.

## Accessibility & Inclusion

The site should support keyboard navigation, visible focus states, semantic headings, readable contrast and responsive reading on small screens.

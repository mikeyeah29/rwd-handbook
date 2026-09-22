# AI-Assisted Plugin and Feature Development

This playbook defines the workflow for building a WordPress plugin or substantial feature with Codex.

The goal is to use AI for implementation while keeping architecture, decisions, testing and final responsibility under human control.

## Core Principle

Give Codex the complete destination, but implement it in independently testable milestones.

Do not ask Codex to build an entire project in one uninterrupted pass. Do not divide work arbitrarily by individual files or classes either.

Each milestone should result in behaviour that can be inspected or tested before continuing.

## 1. Define the Feature

Before writing code, create a short specification covering:

- The problem being solved
- Who will use it
- Required behaviour
- User-facing screens or controls
- External integrations
- Data that must be stored
- Security and permission requirements
- Expected error behaviour
- Explicitly excluded functionality
- Acceptance criteria

Record unresolved decisions as questions rather than allowing Codex to guess.

### Specification Prompt

> Help me turn the following requirements into a build-ready specification. Identify ambiguities, edge cases, security concerns and missing decisions. Do not implement anything yet.
>
> [Paste requirements]

## 2. Prepare Project Instructions

Use `AGENTS.md` for persistent project-wide instructions such as:

- Coding standards
- Architecture preferences
- Supported versions
- Testing and linting commands
- Security requirements
- Repository conventions
- Files or directories that must not be changed

Keep detailed feature requirements in a separate file, such as:

```text
docs/
└── feature-name-spec.md
```

Reference the specification from `AGENTS.md` when appropriate.

## 3. Start From a Safe Git State

Before asking Codex to make changes:

- Check `git status`
- Identify existing uncommitted changes
- Commit or safely preserve unrelated work
- Create a feature branch when appropriate
- Confirm the current site or application still works

Example:

```bash
git status
git switch -c feature/feature-name
```

Never allow Codex to discard existing changes without reviewing exactly what will be removed.

## 4. Ask Codex to Inspect Before Coding

Codex should understand the real codebase before proposing implementation.

Ask it to inspect:

- Repository structure
- Existing conventions
- Relevant classes, hooks and APIs
- Installed dependency versions
- Similar existing functionality
- Available tests and development commands
- Official current documentation for external services

Codex must not invent hooks, field names, endpoints or library behaviour when they can be inspected or verified.

### Inspection Prompt

> Read `AGENTS.md` and the relevant specification completely.
>
> Inspect the repository and the existing implementation related to this feature. Verify any external APIs or framework behaviour against current official documentation.
>
> Do not change any files yet.
>
> Report:
>
> 1. How the relevant existing code works
> 2. The integration points you found
> 3. Important constraints and risks
> 4. Assumptions that need confirmation
> 5. Any requirements that are not currently implementable as written

## 5. Agree an Implementation Plan

Ask Codex to divide the work into independently testable milestones.

Good milestones deliver observable behaviour, for example:

1. Plugin skeleton and dependency checks
2. Settings screen and credential storage
3. External API connection test
4. Manual test workflow
5. Automatic event detection
6. Duplicate prevention and background processing
7. Logging and error handling
8. Final integration testing and cleanup

Avoid milestones such as “create three classes” unless those classes produce testable behaviour.

### Planning Prompt

> Based on your inspection, propose an implementation plan divided into independently testable milestones.
>
> For each milestone include:
>
> - Behaviour delivered
> - Files likely to change
> - Important implementation decisions
> - Automated checks
> - Manual testing steps
> - Dependencies on later milestones
>
> Do not implement anything yet.

Review and approve the plan before implementation begins.

## 6. Implement One Milestone at a Time

Authorise only the next agreed milestone.

Codex may change multiple related files within a milestone. It does not need a separate prompt for every class or function.

### Implementation Prompt

> Implement milestone [number] only from the approved plan.
>
> Follow `AGENTS.md` and the feature specification. Keep the changes focused on this milestone and preserve unrelated work.
>
> Run all relevant automated checks.
>
> When finished, stop and report:
>
> 1. What changed
> 2. Important decisions made
> 3. Checks performed and their results
> 4. Exact manual testing steps
> 5. Known limitations or unresolved issues
>
> Do not begin the next milestone.

## 7. Review the Change

Review the milestone before accepting it.

Check:

- Does the implementation match the specification?
- Has Codex made any undocumented assumptions?
- Is the architecture appropriate rather than unnecessarily complicated?
- Are inputs validated and sanitised?
- Is output escaped?
- Are permissions and nonces checked?
- Are secrets protected?
- Are errors handled without breaking unrelated behaviour?
- Could repeated requests or hooks create duplicate actions?
- Has unrelated code been modified?
- Can the important execution flow be explained clearly?

Useful review commands include:

```bash
git status
git diff --stat
git diff
```

### Code Review Prompt

> Review the current uncommitted changes as a senior WordPress developer.
>
> Look specifically for:
>
> - Incorrect assumptions
> - WordPress security issues
> - Duplicate event or race-condition risks
> - Error-handling problems
> - Backwards-compatibility problems
> - Unnecessary complexity
> - Missing test coverage
> - Changes outside the approved milestone
>
> Do not modify the code yet. Rank findings by severity and explain how each should be resolved.

## 8. Test the Milestone

Run both automated and manual tests.

Automated checks may include:

- PHP syntax checks
- Coding standards
- Static analysis
- Unit tests
- Integration tests
- JavaScript linting
- Build commands

Manual testing should cover:

- The normal successful path
- Invalid input
- Missing dependencies
- Permission failures
- External API failures
- Repeated actions
- Empty or incomplete data
- Existing functionality that could be affected

Do not treat “the code looks correct” as successful testing.

When testing external actions, use a staging site, test account or non-destructive mode wherever possible.

## 9. Fix Issues Before Continuing

If a milestone fails testing, keep the work within that milestone until it passes.

### Fix Prompt

> Milestone [number] failed the following test:
>
> [Describe the expected result, actual result and any error messages.]
>
> Diagnose the root cause before changing code. Then implement the smallest appropriate fix, rerun the relevant checks and explain what changed.
>
> Do not begin another milestone.

Avoid stacking new functionality on top of known broken behaviour.

## 10. Commit the Milestone

Once the milestone has passed:

- Review the final diff
- Confirm no debugging code or secrets remain
- Confirm unrelated files were not changed
- Commit it separately with a meaningful message

Example:

```bash
git add path/to/relevant/files
git commit -m "Add Buffer connection settings"
```

Prefer one logical commit per milestone or independently reversible change.

Do not ask Codex to stage every modified file blindly when unrelated changes exist.

## 11. Continue Through the Plan

Repeat the following loop:

```text
Implement
→ Review
→ Test
→ Fix
→ Commit
→ Continue
```

Update the specification or plan when a new decision is made.

Do not rely on chat history as the only record of important architectural or product decisions.

## 12. Run a Final Project Review

After all milestones are complete, review the feature as one connected system.

### Final Review Prompt

> All approved milestones are now implemented.
>
> Review the complete feature against the original specification and acceptance criteria.
>
> Check:
>
> - End-to-end behaviour
> - Security
> - Data handling
> - Failure and retry behaviour
> - Duplicate prevention
> - Performance
> - Accessibility
> - Compatibility
> - Upgrade and uninstall behaviour
> - Logging
> - Documentation
> - Test coverage
>
> Do not add new functionality. Identify any blockers, important improvements or deviations from the specification.

## 13. Definition of Done

A plugin or feature is complete when:

- All acceptance criteria are satisfied
- Important behaviour has been manually tested
- Automated checks pass
- Failure paths have been tested
- Security-sensitive code has been reviewed
- No secrets or temporary debugging code remain
- No known critical or high-severity issues remain
- Relevant documentation has been updated
- Changes are divided into understandable commits
- The complete execution flow can be explained
- The feature works in an appropriate staging environment

“Codex finished writing the code” is not the definition of done.

## When a Smaller Workflow Is Enough

Not every change needs the complete process.

For a small, low-risk change:

1. Ask Codex to inspect the relevant code
2. Describe the required outcome
3. Ask it to implement the focused change
4. Review the diff
5. Run the relevant tests
6. Commit the change

Use the complete milestone workflow when the work involves:

- A new plugin
- A substantial feature
- External APIs
- Payments or personal data
- Background jobs
- Automated actions
- Authentication or permissions
- Database changes
- Multiple interconnected components
- Behaviour that could affect a live client site

## Working Relationship With Codex

Codex can:

- Inspect unfamiliar code quickly
- Research current technical documentation
- Propose architecture
- Implement related code across multiple files
- Write tests
- Diagnose failures
- Review diffs
- Explain unfamiliar code

The developer remains responsible for:

- Defining the desired outcome
- Making product and architectural decisions
- Resolving ambiguity
- Protecting existing work
- Testing real behaviour
- Reviewing security-sensitive code
- Deciding when the feature is ready to deploy

The aim is not to manually write every line. The aim is to remain in control of what is being built, why it works and how it has been verified.
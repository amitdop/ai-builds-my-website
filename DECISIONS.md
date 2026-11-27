# Project decisions

## Prefer grounded details over plausible guesses

The generator may use only facts supported by the business details or readable uploaded text. It retains source evidence so the owner can review claims. Unsupported details should be reported as missing, not filled in with invented business facts.

## Treat uploaded content as untrusted

Document text is evidence, not an instruction source. The generation prompt says to ignore commands embedded in source text, and the response is validated before it is saved. Public pages escape rendered text and do not display private source evidence or uploaded files.

## Surface conflicts instead of choosing a winner

When sources disagree, the draft reports the competing claims for the owner to review. Unresolved conflicting fields are omitted from the public page. This avoids silently deciding which source is correct.

## Keep owner edits distinct

Owners can edit the generated tagline and facts. Changed facts are marked as owner-edited, and their previous value and source evidence remain available in the private review page. Public pages label owner-edited facts as business-provided.

## Keep draft changes private until republished

Publishing copies the reviewed draft into a separate content snapshot. Editing or regenerating the draft does not overwrite the live page; the owner explicitly republishes to update it.

## Use browser-session ownership for this prototype

The app has no user registration or authentication flow. A random token in the creator's browser session grants access to edit, upload, regenerate, and publish that profile; only its hash is stored in the database. If the session is lost, the profile becomes read-only. This is a prototype tradeoff, not a substitute for account-based access control in a production service.

## Avoid duplicate profiles within one owner session

Before creating a profile, the app checks for the same trimmed, case-insensitive business name and city owned by that browser session. If found, it reopens the most recently created matching profile. Different sessions are kept separate because the app cannot safely assume that two people submitting the same name and city represent the same business.

## Keep external source access honest

Business links are stored but not fetched or scraped. They are reported to the owner as unread sources. Public links are limited to HTTP(S) URLs.

## Document processing is limited to readable PDFs

PDF text extraction is synchronous and bounded. Images are stored but not analyzed, and scanned PDFs need OCR that is not implemented. These limits are disclosed rather than implying files have been fully understood.

## Use Gemini as an optional external service

Draft generation requires a locally configured Gemini API key. Provider failures receive a user-facing message, and the rest of the profile remains saved. Because free-tier data handling is controlled by Google, users are warned not to submit confidential documents.

/**
 * Simple text editor enhancements
 */

document.addEventListener("DOMContentLoaded", () => {
  const contentTextarea = document.getElementById("content")

  if (contentTextarea) {
    // Add toolbar
    const toolbar = document.createElement("div")
    toolbar.className = "editor-toolbar"

    // Bold button
    const boldButton = document.createElement("button")
    boldButton.type = "button"
    boldButton.className = "toolbar-button"
    boldButton.textContent = "Bold"
    boldButton.addEventListener("click", () => {
      insertText(contentTextarea, "**", "**")
    })

    // Italic button
    const italicButton = document.createElement("button")
    italicButton.type = "button"
    italicButton.className = "toolbar-button"
    italicButton.textContent = "Italic"
    italicButton.addEventListener("click", () => {
      insertText(contentTextarea, "_", "_")
    })

    // Link button
    const linkButton = document.createElement("button")
    linkButton.type = "button"
    linkButton.className = "toolbar-button"
    linkButton.textContent = "Link"
    linkButton.addEventListener("click", () => {
      const url = prompt("Enter URL:")
      if (url) {
        insertText(contentTextarea, "[", "](" + url + ")")
      }
    })

    // Add buttons to toolbar
    toolbar.appendChild(boldButton)
    toolbar.appendChild(italicButton)
    toolbar.appendChild(linkButton)

    // Insert toolbar before textarea
    contentTextarea.parentNode.insertBefore(toolbar, contentTextarea)

    // Add styles
    const style = document.createElement("style")
    style.textContent = `
            .editor-toolbar {
                display: flex;
                gap: 0.5rem;
                margin-bottom: 0.5rem;
                padding: 0.5rem;
                background-color: #f8f9fa;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
            }
            
            .toolbar-button {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
                background-color: #fff;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
                cursor: pointer;
            }
            
            .toolbar-button:hover {
                background-color: #e9ecef;
            }
        `
    document.head.appendChild(style)
  }
})

/**
 * Insert text at cursor position or wrap selected text
 */
function insertText(textarea, before, after) {
  const start = textarea.selectionStart
  const end = textarea.selectionEnd
  const selectedText = textarea.value.substring(start, end)
  const beforeText = textarea.value.substring(0, start)
  const afterText = textarea.value.substring(end)

  textarea.value = beforeText + before + selectedText + after + afterText

  // Reset focus and selection
  textarea.focus()
  textarea.selectionStart = start + before.length
  textarea.selectionEnd = start + before.length + selectedText.length
}


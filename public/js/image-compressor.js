/**
 * Chap Connect - Client-side Image Pre-Compressor
 * Automatically resizes & compresses large images in the browser before sending to server.
 * Prevents HTTP 413 / PostTooLargeException errors when uploading large camera photos.
 */

document.addEventListener("DOMContentLoaded", () => {
    const imageInputs = document.querySelectorAll('input[type="file"]');

    imageInputs.forEach((input) => {
        // Skip if input is specifically for video
        if (input.name === 'video' || (input.accept && input.accept.includes('video') && !input.accept.includes('image'))) {
            return;
        }

        // Add feedback status element
        let statusEl = document.createElement("p");
        statusEl.className = "image-optimize-status";
        statusEl.style.cssText = "font-size: 0.8rem; margin-top: 6px; font-weight: 500; display: none;";
        input.parentNode.appendChild(statusEl);

        input.addEventListener("change", async (e) => {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith("image/")) {
                statusEl.style.display = "none";
                return;
            }

            const origSizeMB = (file.size / (1024 * 1024)).toFixed(2);

            // Pre-compress if file size is > 1MB
            if (file.size > 1024 * 1024) {
                const form = input.closest("form");
                const submitBtn = form ? form.querySelector('button[type="submit"]') : null;

                statusEl.style.display = "block";
                statusEl.style.color = "#6366f1";
                statusEl.innerHTML = `⚡ Optimizing large image (${origSizeMB} MB)...`;

                if (submitBtn) {
                    submitBtn.disabled = true;
                    if (!submitBtn.dataset.origText) {
                        submitBtn.dataset.origText = submitBtn.innerText;
                    }
                    submitBtn.innerText = "Compressing Image...";
                }

                try {
                    // Maximum 1920px, 82% JPEG quality compression
                    const compressedFile = await compressImageOnClient(file, 1920, 1920, 0.82);
                    
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    input.files = dataTransfer.files;

                    const newSizeMB = (compressedFile.size / (1024 * 1024)).toFixed(2);
                    const newSizeKB = Math.round(compressedFile.size / 1024);
                    const sizeDisplay = newSizeMB >= 1 ? `${newSizeMB} MB` : `${newSizeKB} KB`;

                    statusEl.style.color = "#10b981";
                    statusEl.innerHTML = `✓ Compressed from ${origSizeMB} MB down to ${sizeDisplay} for fast upload!`;
                } catch (err) {
                    console.warn("Client pre-compression skipped:", err);
                    statusEl.style.color = "#f59e0b";
                    statusEl.innerHTML = `Original image (${origSizeMB} MB) ready for upload.`;
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerText = submitBtn.dataset.origText;
                    }
                }
            } else {
                statusEl.style.display = "block";
                statusEl.style.color = "#10b981";
                statusEl.innerHTML = `✓ Ready for fast upload (${Math.round(file.size / 1024)} KB)`;
            }
        });
    });

    /**
     * Compress image using HTML5 Canvas
     */
    function compressImageOnClient(file, maxWidth, maxHeight, quality) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    let width = img.width;
                    let height = img.height;

                    if (width > maxWidth || height > maxHeight) {
                        const ratio = Math.min(maxWidth / width, maxHeight / height);
                        width = Math.round(width * ratio);
                        height = Math.round(height * ratio);
                    }

                    const canvas = document.createElement("canvas");
                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(
                        (blob) => {
                            if (!blob) {
                                reject(new Error("Canvas to Blob conversion failed"));
                                return;
                            }
                            const newFileName = file.name.replace(/\.[^/.]+$/, "") + "_optimized.jpg";
                            const newFile = new File([blob], newFileName, {
                                type: "image/jpeg",
                                lastModified: Date.now(),
                            });
                            resolve(newFile);
                        },
                        "image/jpeg",
                        quality
                    );
                };
                img.onerror = reject;
                img.src = event.target.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }
});

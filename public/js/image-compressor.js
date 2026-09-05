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
            const rawFiles = e.target.files;
            if (!rawFiles || rawFiles.length === 0) {
                statusEl.style.display = "none";
                return;
            }

            const files = Array.from(rawFiles);
            const imageFiles = files.filter(f => f.type && f.type.startsWith("image/"));

            if (imageFiles.length === 0) {
                statusEl.style.display = "none";
                return;
            }

            const form = input.closest("form");
            const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
            const hasLargeFiles = imageFiles.some(f => f.size > 1024 * 1024);

            if (hasLargeFiles) {
                statusEl.style.display = "block";
                statusEl.style.color = "#6366f1";
                statusEl.innerHTML = `⚡ Optimizing ${imageFiles.length} image(s)...`;

                if (submitBtn) {
                    submitBtn.disabled = true;
                    if (!submitBtn.dataset.origText) {
                        submitBtn.dataset.origText = submitBtn.innerText;
                    }
                    submitBtn.innerText = "Compressing Image(s)...";
                }

                try {
                    const dataTransfer = new DataTransfer();
                    for (const file of files) {
                        if (file.type && file.type.startsWith("image/") && file.size > 1024 * 1024) {
                            try {
                                const compressed = await compressImageOnClient(file, 1920, 1920, 0.82);
                                dataTransfer.items.add(compressed);
                            } catch (err) {
                                console.warn("Pre-compression skipped for:", file.name, err);
                                dataTransfer.items.add(file);
                            }
                        } else {
                            dataTransfer.items.add(file);
                        }
                    }

                    if (dataTransfer.files.length > 0) {
                        input.files = dataTransfer.files;
                    }

                    statusEl.style.color = "#10b981";
                    statusEl.innerHTML = `✓ ${imageFiles.length} image(s) optimized & ready for upload!`;
                } catch (err) {
                    console.warn("Client pre-compression skipped:", err);
                    statusEl.style.color = "#10b981";
                    statusEl.innerHTML = `✓ ${files.length} file(s) ready for upload.`;
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        if (submitBtn.dataset.origText) {
                            submitBtn.innerText = submitBtn.dataset.origText;
                        }
                    }
                }
            } else {
                statusEl.style.display = "block";
                statusEl.style.color = "#10b981";
                statusEl.innerHTML = `✓ ${files.length} file(s) ready for fast upload.`;
                if (submitBtn) {
                    submitBtn.disabled = false;
                    if (submitBtn.dataset.origText) {
                        submitBtn.innerText = submitBtn.dataset.origText;
                    }
                }
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

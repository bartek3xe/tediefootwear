document.addEventListener('DOMContentLoaded', () => {
    const filepond = document.querySelector('.filepond');

    if (!filepond) {
        throw new Error('c607868d-ab39-4fd4-81f8-56010b818f89');
    }

    const productId = filepond.getAttribute('data-product-id');
    const productJsonFiles = filepond.getAttribute('data-product-files');

    if (!productJsonFiles) {
        throw new Error('e2162bc3-90b9-4221-b632-82d5abbcf9db');
    }

    function parseJsonFiles(jsonFiles) {
        try {
            return JSON.parse(jsonFiles);
        } catch (e) {
            throw new Error('d41f23f7-b2c4-41c2-bbda-3c6a3f9a8f1f');
        }
    }

    function mapExistingFiles(files) {
        return files.map((file) => ({
            source: file.filename,
            options: {
                type: 'local',
                file: {
                    name: file.filename,
                    size: file.size,
                    type: `image/${file.extension}`,
                },
            },
        }));
    }

    function handleXhrOnLoad(xhr, load, error, uuid) {
        if (xhr.status >= 200 && xhr.status < 300) {
            load();
        } else {
            error(`Could not complete the request: ${xhr.statusText}`);
            throw new Error(uuid);
        }
    }

    function handleXhrOnError(error, uuid) {
        error('Network error');
        throw new Error(uuid);
    }

    function loadFile(source, load, error) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `/admin/upload/load/${source}`);
        xhr.responseType = 'blob';

        xhr.onload = () => handleXhrOnLoad(xhr, load, error, '1a9b51f2-8a1c-4c36-b1ec-c497d1c7d5d5');
        xhr.onerror = () => handleXhrOnError(error, '6f3478b3-5f7d-439f-b8a5-8fa0c7286f2b');

        xhr.send();
    }

    function extractFilenameFromUniqueFileId(uniqueFileId) {
        try {
            const parsed = JSON.parse(uniqueFileId);
            return parsed.id;
        } catch (e) {
            throw new Error('23ef15c4-33d3-4a2e-b6e1-3f4a3f0a9e92');
        }
    }

    const parsedFiles = parseJsonFiles(productJsonFiles);
    const existingFiles = mapExistingFiles(parsedFiles);

    const deleteFile = (filename, load, error) => {
        const xhr = new XMLHttpRequest();
        xhr.open('DELETE', `/admin/upload/revert/${filename}`);

        xhr.onload = () => handleXhrOnLoad(xhr, load, error, 'a9d2f9b8-d3e0-4f9e-8610-bc1e99d263c2');
        xhr.onerror = () => handleXhrOnError(error, 'b16d8f20-3d95-4b9c-85cf-2c2d8f2a2f0c');

        xhr.send();
    };

    const reorderFiles = (fileList) => {
        const orderedFiles = fileList.map((item) => item.file.name);

        fetch(`/admin/upload/reorder/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ order: orderedFiles }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (!data.success) {
                    throw new Error('ba7fda5a-b9dc-41c6-b8c0-d43c65207621');
                }
            });
    };

    // eslint-disable-next-line no-undef
    FilePond.create(filepond, {
        maxFiles: 10,
        allowMultiple: true,
        files: existingFiles,
        server: {
            process: {
                url: `/admin/upload/process/${productId}`,
                method: 'POST',
                withCredentials: false,
                timeout: 7000,
            },
            remove: (source, load, error) => deleteFile(source, load, error),
            revert: (uniqueFileId, load, error) => {
                const filename = extractFilenameFromUniqueFileId(uniqueFileId);
                deleteFile(filename, load, error);
            },
            load: (source, load, error) => loadFile(source, load, error),
        },
        allowReorder: true,
        allowProcess: true,
        allowRemove: true,
        onreorderfiles: reorderFiles,
    });
});

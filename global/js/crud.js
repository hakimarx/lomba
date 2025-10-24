   /* going to deprecated ,belum sempat, masih digunakan di form event*/
   
   function hapus(id) {
        if (!confirm("yakin ingin menghapus data?")) return;

        gi("crud").value = "hapus";
        gi("crudid").value = id;
        document.forms['formcrud'].submit();
    }

    function popup(isopen) {
        if (isopen) {
            gi("popup").style.display = "flex";
        } else {
            gi("popup").style.display = "none";
        }

    }

    function tambah() {
        gi("crud").value = "tambah";

        vkolom.forEach((item)=>{
            qs("[name='" + item + "'").value="";
        });

        popup(true);
    }
    function edit(id) {
        gi("crud").value = "edit";
        gi("crudid").value = id;

        vkolom.forEach((item)=>{
            qs("[name='" + item + "'").value=gi("td"+id+item).getAttribute("nilai");
        });

        popup(true);

    }
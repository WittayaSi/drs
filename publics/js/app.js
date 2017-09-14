const app = new Vue({
    http: {
        root: '/root',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('#token').getAttribute('value')
        }
    },
    el: '#app',
    data: {
        pagination: {
            per_page: 20,
            from: 1,
            to: 20,
            current_page: 1,
            last_page: 0
        },
        offset: 4,

        initial: 1,
        thailand: {
            province: [],
            district: "",
            sub_district: ""
        },
        sel_date: {
            date1: moment().format('YYYY-MM-01'),
            date2: moment().format('YYYY-MM-' + moment().daysInMonth())
        },

        selectedResults: {},

        allProvinces: {},
        allDistricts: {},
        allSubdistricts: {},

        allDistrictsSelected: {},

        allSubdistrictsSelected: {},
        allDistrictWithProvince: {},

        //pSelectDisable: true,
        dSelectDisable: true,
        sSelectDisable: true,

        pClicked: false,
        dClicked: false,
        sClicked: false,

        pfDisabled: false,
        pvDisabled: false,
        mixDisabled: false,

        loadingPageCompleted: false,

        provinceSelected: [],
        resultStatus: false,
        no_of_data: 0,
        data_loading: false,
        check_box: {
            checkDay3: false,
            checkDay7: false,
            checkDay14: false,
            checkDay42: false,
            checkDay28: false,
            checkPf: false,
            checkPv: false,
            checkMix: false,
            checkOther: false
        },
        url_download_xls: '',
        url_download_xlsx: '',
        url_download_csv: '',
        page: 1,
        enableFirefoxSection: false,
        showAddressDetialStatus: false
    },

    mounted(){
//        console.log(browser)
//        if(browser.name == 'firefox'){
//            this.enableFirefoxSection = true
//        }

        this.getData(this.pagination.current_page)
    },

    computed: {
        returnProvinceName: function() {
            this.provinceSelected = []
            if(this.thailand.province != ""){
                this.thailand.province.forEach((p)=>{
                    this.provinceSelected.push(this.allProvinces.filter((v)=>{
                        return v.PROVINCE_ID == p
                    }))
                })
                console.log(this.provinceSelected)
                return this.provinceSelected
            }
        },
        isActived: function() {
            return this.pagination.current_page;
        },
        pagesNumber: function() {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + (this.offset);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },

    methods:{
        showAddressDetial(){
            this.showAddressDetialStatus = !this.showAddressDetialStatus
            console.log(this.showAddressDetialStatus)
        },
        removeItemProvince(p_id){
            console.log('remove item from province array!!! ' + p_id)
            console.log(this.thailand.province)
            let index = this.thailand.province.indexOf(p_id)
            this.thailand.province.splice(index, 1);
            if(this.thailand.province == "" ){
                this.thailand.district = ""
                this.thailand.sub_district = ""
                this.onChangedProvince()
            }else{
                console.log(this.thailand.province)
                this.onChangedProvince()
            }
        },
        getData(page){
            this.data_loading = true
            this.page = page
            const object = this.creatObject()
            console.log(object)
            //axios.post('/DRS/home/getResultsOnChangedEventSection', object)
            axios.post('api/getResultsOnChangedEventSection', object)
            .then((res) => {
                console.log(res.data)
                if(this.initial == 1){
                    this.allProvinces = res.data.provinces
                }
                this.selectedResults = res.data.pagination.data
                this.pagination = res.data.pagination
                this.no_of_data = res.data.total
                this.pagination.last_page = res.data.lastPage
                if(this.no_of_data > 0){
                    this.pSelectDisable = false
                    this.resultStatus = true
                }
                this.loadingPageCompleted = true

                this.data_loading = false
                this.initial = 0

                //this.createUrl(object)

            }).catch((error) => {
                console.log(error)
            })
        },
        changePage(page) {
            this.pagination.current_page = page;
            this.getData(page);
        },
        creatObject(t){
            const sCheckBox = (this.check_box.checkDay3 ? '1' : '0')
                + (this.check_box.checkDay7 ? '1' : '0')
                + (this.check_box.checkDay14 ? '1' : '0')
                + (this.check_box.checkDay42 ? '1' : '0')
                + (this.check_box.checkDay28 ? '1' : '0')
            const sCheckBoxBlood = (this.check_box.checkPf ? '1' : '0')
                + (this.check_box.checkPv ? '1' : '0')
                + (this.check_box.checkMix ? '1' : '0')
            return {
                date1: this.sel_date.date1,
                date2: this.sel_date.date2,
                p: this.thailand.province,
                d: this.thailand.district,
                s: this.thailand.sub_district,
                c: sCheckBox,
                cb: sCheckBoxBlood,
                other: this.check_box.checkOther,
                page: this.page,
                t: t
            }
        },
        createUrl(type){
            const object = this.creatObject(type)
            // return '/home/exportExcelFile?object=' + object
            return 'api/exportExcelFile'
                            +'?date1='+object.date1
                            +'&date2='+object.date2
                            +'&p='+object.p
                            +'&d='+object.d
                            +'&s='+object.s
                            +'&c='+object.c
                            +'&cb='+object.cb
                            +'&other='+object.other
                            +'&t='+object.t

            // this.url_download_xls = '/home/exportExcelFile'
            //                 +'?date1='+object.date1
            //                 +'&date2='+object.date2
            //                 +'&p='+object.p
            //                 +'&d='+object.d
            //                 +'&s='+object.s
            //                 +'&c='+object.c
            //                 +'&t=xls'

            // this.url_download_xlsx = '/home/exportExcelFile'
            //                 +'?date1='+object.date1
            //                 +'&date2='+object.date2
            //                 +'&p='+object.p
            //                 +'&d='+object.d
            //                 +'&s='+object.s
            //                 +'&c='+object.c
            //                 +'&t=xlsx'

            // this.url_download_xls = '/home/exportExcelFile'
            //                 +'?date1='+object.date1
            //                 +'&date2='+object.date2
            //                 +'&p='+object.p
            //                 +'&d='+object.d
            //                 +'&s='+object.s
            //                 +'&c='+object.c
            //                 +'&t=csv'
        },
        onChangedDate(){
            console.log('onChangedDate')
            this.data_loading = true
            this.page = 1
            const object = this.creatObject()
            //axios.post('/DRS/home/getResultsOnChangedEventSection', object)
            axios.post('api/getResultsOnChangedEventSection', object)
            .then((res) => {
                this.selectedResults = res.data.pagination.data
                this.pagination = res.data.pagination
                this.no_of_data = res.data.total
                this.pagination.last_page = res.data.lastPage
                console.log(res.data)
                if((this.no_of_data > 0) && (this.pClicked) && (this.thailand.province == "")){
                    console.log('11111111111')
                    this.resultStatus = true
                    this.dSelectDisable = true
                    this.thailand.district = ""
                    this.sSelectDisable = true
                    this.thailand.sub_district = ""
                }else if((this.no_of_data > 0) && (this.thailand.province != "")){
                    console.log('222222222')
                    this.resultStatus = true
                    this.dSelectDisable = false
                }else if(this.no_of_data > 0){
                    console.log('3333333333')
                    this.resultStatus = true
                }else{
                    console.log('44444444444')
                    //this.thailand.province = []
                    this.dSelectDisable = true
                    this.thailand.district = ""
                    this.sSelectDisable = true
                    this.thailand.sub_district = ""
                    this.resultStatus = false
                }
                this.data_loading = false

                //this.createUrl(object)

            }).catch((error) => {
                console.log(error)
            })
        },

        onChangedProvince(){
            console.log('onChangedProvince')
            if(this.thailand.province.length > 0){
                this.data_loading = true
                this.pClicked = true
                this.page = 1
                const object = this.creatObject()
                console.log(object)
                //axios.post('/DRS/home/getResultsOnChangedEventSection', object)
                axios.post('api/getResultsOnChangedEventSection', object)
                .then((res)=>{
                    console.log(res.data)
                    this.selectedResults = res.data.pagination.data
                    this.pagination = res.data.pagination
                    this.no_of_data = res.data.total

                    this.pagination.last_page = res.data.lastPage
                    this.allDistrictsSelected = res.data.selectedDistricts
                    if((this.no_of_data > 0) && (this.dClicked)){
                        this.resultStatus = true
                        this.dSelectDisable = false
                        this.thailand.district = ""
                        this.sSelectDisable = true
                        this.thailand.sub_district =""
                    }else if(this.no_of_data > 0){
                        this.resultStatus = true
                        this.dSelectDisable = false
                    }else{
                        this.resultStatus = false
                        this.dSelectDisable = true
                        this.sSelectDisable = true
                        this.thailand.district = ""
                        this.thailand.sub_district = ""
                    }
                    this.data_loading = false
                }).catch((error)=>{
                    console.log(error)
                })
            }else if(this.pClicked){
                this.onChangedDate()
            }
        },

        onChangedDistrict(){
            console.log('onChangedDistrict')
            if(this.thailand.district != ""){
                this.data_loading = true
                this.dClicked = true
                this.page = 1
                const object = this.creatObject()
                //axios.post('/DRS/home/getResultsOnChangedEventSection', object)
                axios.post('api/getResultsOnChangedEventSection', object)
                .then((res)=>{
                    this.selectedResults = res.data.pagination.data
                    this.pagination = res.data.pagination
                    this.no_of_data = res.data.total
                    this.pagination.current_page = 1
                    this.pagination.last_page = res.data.lastPage
                    this.allSubdistrictsSelected = res.data.selectedSubdistricts
                    console.log(res.data)

                    if((this.no_of_data > 0) && (this.sClicked)){
                        this.resultStatus = true
                        this.sSelectDisable = false
                        this.sClicked = false
                    }else if(this.no_of_data > 0){
                        this.resultStatus = true
                        this.sSelectDisable = false
                    }else{
                        this.resultStatus = false
                        this.sSelectDisable = true
                        this.thailand.sub_district =""
                    }
                    this.data_loading = false
                })
                this.dSelectDisable = false
            }else if(this.dClicked){
                this.onChangedProvince()
            }
        },

        onChangedSubdistrict(){
            console.log('onChangedSubdistrict')
            if(this.thailand.sub_district != ""){
                this.data_loading = true
                this.sClicked = true
                this.page = 1
                const object = this.creatObject()
                //axios.post('/DRS/home/getResultsOnChangedEventSection', object)
                axios.post('api/getResultsOnChangedEventSection', object)
                .then((res)=>{
                    this.selectedResults = res.data.pagination.data
                    this.pagination = res.data.pagination
                    this.no_of_data = res.data.total
                    this.pagination.current_page = 1
                    this.pagination.last_page = res.data.lastPage
                    console.log(res.data)
                    if(this.no_of_data > 0){
                        this.resultStatus = true
                    }else{
                        this.resultStatus = false
                    }
                    this.data_loading = false
                })
            }else if(this.sClicked){
                this.onChangedDistrict()
            }
        },

        onCheckBoxClick(){
            if(this.check_box.checkOther){
                this.pfDisabled = true
                this.check_box.checkPf = false
                this.pvDisabled = true
                this.check_box.checkPv = false
                this.mixDisabled = true
                this.check_box.checkMix = false

            }else{
                this.pfDisabled = false
                this.pvDisabled = false
                this.mixDisabled = false
            }
            this.data_loading = true
            this.page = 1
            const object = this.creatObject()
            console.log(object)
            //axios.post('/DRS/home/getResultsOnCheckBoxClick',object)
            axios.post('api/getResultsOnChangedEventSection', object)
            .then((res)=>{
                console.log(res.data)
                this.selectedResults = res.data.pagination.data
                this.pagination = res.data.pagination
                this.no_of_data = res.data.total
                this.pagination.current_page = 1
                this.pagination.last_page = res.data.lastPage
                if(this.no_of_data > 0){
                    this.resultStatus = true
                    if(this.thailand.province.length > 0){
                        this.dSelectDisable = false
                    }
                }else if(this.thailand.province.length > 0){
                    this.resultStatus = false
                }else{
                    this.resultStatus = false
                }
                this.data_loading = false
            })
            .catch((error)=>{
                console.log(error)
            })
        },
    }
});

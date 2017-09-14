<?php headerview(); ?>

<div id="app">

    <div v-show="!loadingPageCompleted" id="loading"></div>

    <div class="card" style="margin-top: 2rem;">
        <div class="card-header alert alert-success fixed-top" style="text-align: center;">
            Drug Efficacy Surveillance
        </div>
        <div class="card-body" style="margin-top: 20px;">
            <div class="container">
            <div class="row row-new">
                <div class="form-group col-md-auto col-sm-auto col-xs-auto">
                    <label for="sel_date1">เลือกช่วงเวลา</label>
                    <input type="date" min="2010-01-01" :max="sel_date.date2" class="form-control" name="sel_date1_f" id="sel_date1" v-model="sel_date.date1" @change="onChangedDate">
                </div>
                <div class="form-group col-md-auto col-sm-auto col-xs-auto">
                    <label for="sel_date1">&nbsp</label>
                    <input type="date" :min="sel_date.date1" max="2050-01-01" class="form-control" name="sel_date2_f" id="sel_date2" v-model="sel_date.date2" @change="onChangedDate">
                </div>
            </div>

            <hr>

            <div class="row row-new">
                <div class="form-group col-md-4 col-sm-4 col-xs-6">
                    <label for="province">จังหวัด</label>
                    <select multiple class="form-control" ref="province" name="province" id="province" v-model="thailand.province" @change="onChangedProvince">
                        <!-- <option value="">เลือกจังหวัด</option> -->
                        <option v-for="p in allProvinces" :value="p.PROVINCE_ID">{{ p.PROVINCE_NAME_TH }}</option>
                    </select>
                    <small id="emailHelp" class="form-text text-muted">
                        <b v-for="p in returnProvinceName" style="text-decoration: none">
                            {{ p[0].PROVINCE_NAME_TH }} <a href="#" @click.prevent="removeItemProvince(p[0].PROVINCE_ID)"><i class="fa fa-times"></i></a> &nbsp&nbsp&nbsp
                        </b>
                    </small>

                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-6">
                    <label for="district">อำเภอ</label>
                    <select class="form-control" name="district" id="district" v-model="thailand.district" :disabled="dSelectDisable" @change="onChangedDistrict">
                        <option value="">เลือกอำเภอ</option>
                        <option v-for="p in allDistrictsSelected" :value="p.DISTRICT_ID" >{{ p.DISTRICT_NAME_TH }}</option>
                    </select>
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-6">
                    <label for="sub_district">ตำบล</label>
                    <select class="form-control" name="sub_district" id="sub_district" v-model="thailand.sub_district" :disabled="sSelectDisable" @change="onChangedSubdistrict">
                        <option value="">เลือกตำบล</option>
                        <option v-for="p in allSubdistrictsSelected" :value="p.SUBDISTRICT_ID">{{ p.SUBDISTRICT_NAME_TH }}</option>
                    </select>
                </div>
            </div>

            <hr>

            <div class="row row-new check-box">
                <label class="custom-control custom-checkbox col-sm-2 col-xs-2">
                    <input type="checkbox" class="custom-control-input" v-model="check_box.checkPf" @click.prevent="onCheckBoxClick" :disabled="pfDisabled">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Pf</span>
                </label>
                <label class="custom-control custom-checkbox col-sm-2 col-xs-2">
                    <input type="checkbox" class="custom-control-input" v-model="check_box.checkPv" @click.prevent="onCheckBoxClick" :disabled="pvDisabled">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Pv</span>
                </label>
                <label class="custom-control custom-checkbox col-sm-2 col-xs-2">
                    <input type="checkbox" class="custom-control-input" v-model="check_box.checkMix" @click.prevent="onCheckBoxClick" :disabled="mixDisabled">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Mix</span>
                </label>
                <label class="custom-control custom-checkbox col-sm-2 col-xs-2">
                    <input type="checkbox" class="custom-control-input" v-model="check_box.checkOther" @click.prevent="onCheckBoxClick">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Other( อื่นๆ )</span>
                </label>
            </div>

            <div class="row row-new check-box">

                <label class="custom-control custom-checkbox col-sm-2 col-xs-2">
                    <input type="checkbox" class="custom-control-input" v-model="check_box.checkDay3" @click.prevent="onCheckBoxClick">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Day3 positive</span>
                </label>
                <label class="custom-control custom-checkbox col-sm-2 col-xs-2">
                    <input type="checkbox" class="custom-control-input" v-model="check_box.checkDay7" @click.prevent="onCheckBoxClick">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Day7 positive</span>
                </label>
                <label class="custom-control custom-checkbox col-sm-2 col-xs-2">
                    <input type="checkbox" class="custom-control-input" v-model="check_box.checkDay14" @click.prevent="onCheckBoxClick">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Day14 positive</span>
                </label>
                <label class="custom-control custom-checkbox col-sm-2 col-xs-2">
                    <input type="checkbox" class="custom-control-input" v-model="check_box.checkDay28" @click.prevent="onCheckBoxClick">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Day28 positive</span>
                </label>
                <label class="custom-control custom-checkbox col-sm-2 col-xs-2">
                    <input type="checkbox" class="custom-control-input" v-model="check_box.checkDay42" @click.prevent="onCheckBoxClick">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Day42 positive</span>
                </label>
            </div>

            </div>

            <hr>

            <br>

            <div class="row alert alert-info" v-if="resultStatus && !data_loading">
                <div class="col-auto mr-auto">
                    *** แสดง {{ pagination.from }} ถึง {{ pagination.to }} ( หน้า {{ page }} ) ( จากทั้งหมด {{ this.data_loading ? '...' : this.no_of_data }} ราย )
                </div>
                <div class="col-auto">
                    <a :href="createUrl('xls')" class="btn btn-sm btn-success">
                        <small>Export Excel</small>
                    </a>
<!--
                    <a :href="createUrl('xlsx')" class="btn btn-sm btn-success">
                        <small>Export XLXS</small>
                    </a>
-->
                    <!-- <a :href="createUrl('pdf')" class="btn btn-sm btn-success">
                        <small>Export PDF</small>
                    </a> -->
                </div>
            </div>

            <div class="alert alert-info" v-if="data_loading">
                <center>
                    กำลังโหลดข้อมูล......
                </center>
            </div>

            <div class="row">

                <template v-if="resultStatus && !data_loading">
                <div class="table-area" id="table-fade-in">
                    <table class="table table-bordered table-hover table-responsive result-table">
                        <thead>
                            <tr class="alert-info">
                                <th style="text-align: center">treatment place</th>
                                <th style="text-align: center">blood draw date</th>
                                <th style="text-align: center" nowrap>ชื่อ - สกุล</th>
                                <th style="text-align: center">ประเภทผู้ป่วย</th>
                                <th style="text-align: center">เพศ</th>
                                <th style="text-align: center">อายุ (ปี)</th>
                                <th style="text-align: center">อาชีพ</th>

                                <template v-if="showAddressDetialStatus">
                                    <th style="text-align: center">หมู่บ้าน</th>
                                    <th style="text-align: center">หมู่</th>
                                    <th style="text-align: center">ตำบล</th>
                                    <th style="text-align: center">อำเภอ</th>
                                </template>

                                <th style="text-align: center">
                                    <a href="#table-fade-in" style="text-decoration: none" @click="showAddressDetial"
                                        v-html = "showAddressDetialStatus == true ? 'จังหวัด >-< ' : 'จังหวัด <-> '">
                                    </a>
                                </th>
                                <th style="text-align: center">ผลตรวจ</th>
                                <th style="text-align: center">ep1 ep3</th>
                                <th style="text-align: center">med act</th>
                                <th style="text-align: center">fu dot</th>
                                <th style="text-align: center">fu 3</th>
                                <th style="text-align: center">fu 3_ring</th>
                                <th style="text-align: center">fu 7</th>
                                <th style="text-align: center">fu 14</th>
                                <th style="text-align: center">fu 21</th>
                                <th style="text-align: center">fu 28</th>
                                <th style="text-align: center">fu 35</th>
                                <th style="text-align: center">fu 42</th>
                                <th style="text-align: center">fu 60</th>
                                <th style="text-align: center">fu 90</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="p in selectedResults">
                                <td style="text-align: center" nowrap>{{ (p.treatmaent_place_detail == "") || (p.treatmaent_place_detail == null) ? "-":p.treatmaent_place_detail}}</td>
                                <td style="text-align: center" nowrap>{{ p.blood_draw_date}}</td>
                                <td style="text-align: left" nowrap>{{ p.p_name}}</td>
                                <td style="text-align: center" nowrap>{{ p.type_patient_detail }}</td>
                                <td style="text-align: center" nowrap v-text="p.sex == 'M' ? 'ชาย' : 'หญิง'"></td>
                                <td style="text-align: center" nowrap>{{ p.age_year }}</td>
                                <td style="text-align: center" nowrap>{{ (p.p_occupation_detail=="")||(p.p_occupation_detail==null)?"-":p.p_occupation_detail }}</td>

                                <template v-if="showAddressDetialStatus">
                                    <td style="text-align: center" nowrap>{{ (p.p_site_village_name=="")||(p.p_site_village_name==null)?"-":p.p_site_village_name }}</td>
                                    <td style="text-align: center" nowrap>{{ (p.p_site_mu_name=="")||(p.p_site_mu_name==null)?"-":p.p_site_mu_name }}</td>
                                    <td style="text-align: center" nowrap>{{ p.p_site_subdistrict_name }}</td>
                                    <td style="text-align: center" nowrap>{{ p.p_site_district_name }}</td>
                                </template>

                                <td style="text-align: center" nowrap>{{ p.p_site_province_name }}</td>
                                <td style="text-align: center" nowrap>{{ (p.result_code_detail_1=="")||(p.result_code_detail_1==null)?"-":p.result_code_detail_1 }}</td>
                                <td style="text-align: center" nowrap>{{ (p.ep1_ep3=="")||(p.ep1_ep3==null)?"-":p.ep1_ep3 }}</td>
                                <td style="text-align: center" nowrap>{{ (p.med_act=="")||(p.med_act==null)?"-":(p.med_act == "U" ? "Unknow": p.med_act) }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_dot=="")||(p.fu_dot==null)?"-":p.fu_dot }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_3=="")||(p.fu_3==null)?"-":p.fu_3 }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_3_ring=="")||(p.fu_3_ring==null)?"-":p.fu_3_ring }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_7=="")||(p.fu_7==null)?"-":p.fu_7 }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_14=="")||(p.fu_14==null)?"-":p.fu_14 }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_21=="")||(p.fu_21==null)?"-":p.fu_21 }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_28=="")||(p.fu_28==null)?"-":p.fu_28 }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_35=="")||(p.fu_35==null)?"-":p.fu_35 }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_42=="")||(p.fu_42==null)?"-":p.fu_42 }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_60=="")||(p.fu_60==null)?"-":p.fu_60 }}</td>
                                <td style="text-align: center" nowrap>{{ (p.fu_90=="")||(p.fu_90==null)?"-":p.fu_90 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <nav>
                    <ul class="pagination" id="nav-page">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a  href="#" class="page-link" @click.prevent="changePage(1)"><i class="fa fa-angle-double-left"></i></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a href="#" class="page-link" @click.prevent="changePage(pagination.current_page - 1)"><i class="fa fa-angle-left"></i></a>
                        </li>
                        <li v-for="page in pagesNumber" :class="[ page == isActived ? 'page-item active' : '']">
                            <a href="#" class="page-link" @click.prevent="changePage(page)">{{ page }}</a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                          <a href="#"  class="page-link" @click.prevent="changePage(pagination.current_page + 1)"><i class="fa fa-angle-right"></i></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                          <a href="#"  class="page-link" @click.prevent="changePage(pagination.last_page)"><i class="fa fa-angle-double-right"></i></a>
                        </li>
                    </ul>
                </nav>
                <!-- End Pagintaion -->
                </template>
                <template v-if="!resultStatus && !data_loading">
                    <div class="alert alert-danger col" id="no-result-fade-in">
                        <center>ไม่มีข้อมูล</center>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<?php footerview(); ?>

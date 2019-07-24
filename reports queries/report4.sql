select e.name, count(*) as total from order_items oit
inner join equipments e on e.id = oit.equipment_id
group by oit.equipment_id
order by total desc limit 5